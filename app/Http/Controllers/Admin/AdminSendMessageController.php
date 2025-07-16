<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgendaHarian;
use Illuminate\Support\Carbon;
use App\Models\Booking;
use App\Services\WhatsAppAPIService; // Pastikan namespace benar

class AdminSendMessageController extends Controller
{
    /**
     * Menampilkan halaman indeks untuk mengelola agenda harian dan booking.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'desc');
        $periode = $request->input('periode', 'all');
        $query = AgendaHarian::query();
        
        // Filter pencarian
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q) {
                $sub->where('ootd_cewek', 'like', "%$q%")
                    ->orWhere('ootd_cowok', 'like', "%$q%")
                    ->orWhereRaw("JSON_SEARCH(agenda_manual, 'one', ?) IS NOT NULL", [$q]);
                // Cek jika q adalah tanggal
                $tanggal = null;
                try {
                    $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $q);
                } catch (\Exception $e) {}
                if (!$tanggal) {
                    try { $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $q); } catch (\Exception $e) {}
                }
                if (!$tanggal) {
                    try { $tanggal = \Carbon\Carbon::parse($q); } catch (\Exception $e) {}
                }
                if ($tanggal) {
                    $sub->orWhereDate('tanggal', $tanggal->format('Y-m-d'));
                }
                // Cek jika q adalah nama bulan (Indonesia/Inggris, pendek/panjang)
                $bulanIndonesia = [
                    'januari', 'februari', 'maret', 'april', 'mei', 'juni',
                    'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
                ];
                $bulanInggris = [
                    'january', 'february', 'march', 'april', 'may', 'june',
                    'july', 'august', 'september', 'october', 'november', 'december'
                ];
                $bulanPendek = ['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des'];
                $bulanPendekInggris = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
                
                $qLower = strtolower($q);
                if (in_array($qLower, $bulanIndonesia) || in_array($qLower, $bulanInggris) || 
                    in_array($qLower, $bulanPendek) || in_array($qLower, $bulanPendekInggris)) {
                    $bulanIndex = array_search($qLower, $bulanIndonesia);
                    if ($bulanIndex === false) $bulanIndex = array_search($qLower, $bulanInggris);
                    if ($bulanIndex === false) $bulanIndex = array_search($qLower, $bulanPendek);
                    if ($bulanIndex === false) $bulanIndex = array_search($qLower, $bulanPendekInggris);
                    
                    if ($bulanIndex !== false) {
                        $bulanIndex++; // Array dimulai dari 0, bulan dimulai dari 1
                        $sub->orWhereMonth('tanggal', $bulanIndex);
                    }
                }
            });
        }
        
        // Filter periode
        if ($periode === 'minggu') {
            $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($periode === 'bulan') {
            $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        }
        
        // Sorting
        if ($sort === 'asc') {
            $query->orderBy('tanggal', 'asc');
        } else {
            $query->orderBy('tanggal', 'desc');
        }
        
        $agendas = $query->paginate(10);
        
        // Ambil booking untuk 15 hari ke depan dan 1 hari ke belakang untuk ditampilkan di tabel agenda booking
        $bookings = Booking::whereBetween('booking_date', [now()->subDays(1)->toDateString(), now()->addDays(14)->toDateString()])
            ->with(['facility', 'user']) // Eager loading untuk performa
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();
            
        $history = $agendas;
        return view('admin.send_message.index', compact('agendas', 'history', 'bookings'));
    }

    /**
     * Menyimpan atau memperbarui agenda harian.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ootd' => 'required|array',
                'ootd.*.date' => 'required|date',
                'ootd.*.cewek' => 'nullable|string|max:255',
                'ootd.*.cowok' => 'nullable|string|max:255',
                'agenda_manual' => 'nullable|array',
                'agenda_manual.*.*.jam' => 'nullable|string|max:50',
                'agenda_manual.*.*.judul' => 'nullable|string|max:500',
                'agenda_manual.*.*.lokasi' => 'nullable|string|max:255',
                'agenda_manual.*.*.date' => 'nullable|date',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validasi gagal saat simpan agenda harian', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $ootd = $validated['ootd'];
        $agendaManual = $request->input('agenda_manual', []);

        foreach ($ootd as $i => $row) {
            $tanggal = $row['date'];
            $ootd_cewek = $row['cewek'] ?? null;
            $ootd_cowok = $row['cowok'] ?? null;

            // Simpan atau update agenda_harian
            $agendaHarian = AgendaHarian::updateOrCreate(
                ['tanggal' => $tanggal],
                [
                    'ootd_cewek' => $ootd_cewek,
                    'ootd_cowok' => $ootd_cowok,
                    'status_kirim' => 'belum',
                ]
            );

            // Hapus agenda manual lama untuk hari ini
            $agendaHarian->agendaManuals()->delete();
            // Simpan agenda manual baru
            if (isset($agendaManual[$i]) && is_array($agendaManual[$i])) {
                foreach ($agendaManual[$i] as $item) {
                    if (($item['date'] ?? null) === $tanggal && !empty($item['jam']) && !empty($item['judul'])) {
                        $agendaHarian->agendaManuals()->create([
                            'jam' => $item['jam'],
                            'jam_selesai' => $item['jam_selesai'] ?? null,
                            'judul' => $item['judul'],
                            'lokasi' => $item['lokasi'] ?? null,
                        ]);
                    }
                }
            }
        }
        return redirect()->route('admin.send_message.index')->with('success', 'Agenda harian berhasil disimpan!');
    }

    /**
     * Menghasilkan teks isi pesan agenda untuk parameter kedua ({{2}}) dari template WhatsApp Qontak.
     * Fungsi ini TIDAK akan menambahkan salam pembuka atau penutup, karena sudah ada di template Qontak.
     *
     * @param \App\Models\AgendaHarian $agenda
     * @param \Illuminate\Database\Eloquent\Collection $bookings
     * @return string
     */
    public function generateMessageBody(AgendaHarian $agenda, $bookings, $singleLine = false)
    {
        $lines = [];
        $counter = 1;

        // OOTD as first item
        $ootdCewek = $agenda->ootd_cewek ?? '-';
        $ootdCowok = $agenda->ootd_cowok ?? '-';
        $lines[] = $counter . ". 👕 Cewek : " . $ootdCewek . " 👕 Cowok : " . $ootdCowok;
        $counter++;

        // Agenda Manual (prioritaskan manual di atas booking)
        if ($agenda->agendaManuals && count($agenda->agendaManuals) > 0) {
            foreach ($agenda->agendaManuals as $item) {
                $jam = $item->jam ?? '-';
                $jamSelesai = $item->jam_selesai ?? null;
                $judul = $item->judul ?? '-';
                $lokasi = !empty($item->lokasi) ? $item->lokasi : null;
                $icon = strpos(strtolower($judul), 'briefing') !== false ? '📚' : '📒';
                $waktu = $jam;
                if ($jamSelesai) {
                    $waktu .= ' sd ' . $jamSelesai;
                }
                $desc = $waktu . ' ' . $icon . ' ' . $judul;
                if ($lokasi) {
                    $desc .= ' 📍' . $lokasi;
                }
                $lines[] = $counter . '. ' . $desc;
                $counter++;
            }
        }

        // Agenda Booking (setelah manual)
        if ($bookings->isNotEmpty()) {
            foreach ($bookings as $booking) {
                // Pastikan booking_date dan agenda->tanggal dibandingkan sebagai string Y-m-d
                $startDate = $booking->booking_date instanceof \Carbon\Carbon
                    ? $booking->booking_date->format('Y-m-d')
                    : date('Y-m-d', strtotime($booking->booking_date));
                $agendaDate = $agenda->tanggal instanceof \Carbon\Carbon
                    ? $agenda->tanggal->format('Y-m-d')
                    : date('Y-m-d', strtotime($agenda->tanggal));
                $startTime = $booking->booking_time instanceof \Carbon\Carbon
                    ? $booking->booking_time->format('H:i')
                    : date('H:i', strtotime($booking->booking_time));
                $endTime = $booking->booking_end instanceof \Carbon\Carbon
                    ? $booking->booking_end->format('H:i')
                    : date('H:i', strtotime($booking->booking_end));
                // Jika tanggal booking sama dengan agenda harian, hanya tampilkan jam
                if ($startDate === $agendaDate) {
                    $waktu = $startTime . ' sd ' . $endTime;
                } else {
                    $waktu = $startDate . ' ' . $startTime . ' sd ' . $endTime;
                }
                $judul = $booking->meeting_title ?? 'Tanpa Judul';
                $ruangan = $booking->facility->name ?? 'Ruangan Tidak Diketahui';
                $pemesan = $booking->user->name ?? 'Pemesan Tidak Diketahui';
                $desc = $waktu . ' 📒 ' . $judul . ' 📍' . $ruangan . ' (' . $pemesan . ')';
                $lines[] = $counter . '. ' . $desc;
                $counter++;
            }
        }

        if ($singleLine) {
            // Gabungkan semua baris dengan separator " | "
            return implode(' | ', $lines);
        } else {
            return implode("\n", $lines);
        }
    }

    /**
     * Menampilkan detail agenda harian dan preview pesan.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        Carbon::setLocale('id');
        $agenda = AgendaHarian::findOrFail($id);
        $agendaManuals = $agenda->agendaManuals; // relasi baru
        $bookings = Booking::where('booking_date', $agenda->tanggal->toDateString())
            ->where('status', 'Disetujui')
            ->with(['facility', 'user'])
            ->orderBy('booking_time')
            ->get();

        $key1 = $agenda->tanggal->isoFormat('dddd, D MMMM Y');
        $key2 = $this->generateMessageBody($agenda, $bookings, true);
        $previewBody = $this->generateMessageBody($agenda, $bookings, false);
        $pesanPreview = "Selamat pagi teman - teman, selamat mengawali hari ini penuh rasa syukur dan sehat selalu, adapun agenda dihari " . $key1 . " sbb:\n\n" . $previewBody . "\n\nTerima Kasih 🙏 \nTuhan memberkati 😇 \nBisa-Harus Bisa-Pasti Bisa \n#KolaborasiDalamHarmoni 🤲🙏😇💪💪🔥🔥";

        return view('admin.send_message.show', compact('agenda', 'agendaManuals', 'bookings', 'pesanPreview'));
    }

    /**
     * Mengirim pesan agenda harian ke Bu Fitri via Qontak API.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(Request $request, $id)
    {
        Carbon::setLocale('id');
        $agenda = AgendaHarian::findOrFail($id);
        
        // Ambil booking yang sesuai dengan tanggal agenda harian
        $bookings = Booking::where('booking_date', $agenda->tanggal->toDateString())
            ->where('status', 'Disetujui') // Hanya booking yang sudah disetujui
            ->with(['facility', 'user'])
            ->orderBy('booking_time')
            ->get();

        $nomorBuFitri = config('app.bu_fitri_whatsapp_number'); // Mengambil nomor Bu Fitri dari config
        $templateId = config('app.agenda_daily_template_id'); // Menggunakan template ID Qontak untuk agenda harian

        // Siapkan parameter untuk template Qontak
        $key1 = $agenda->tanggal->isoFormat('dddd, D MMMM Y');
        $key2 = $this->generateMessageBody($agenda, $bookings, true); // Hasil generateMessageBody satu baris untuk Qontak

        try {
            app(WhatsAppAPIService::class)->sendQontakTemplate(
                $nomorBuFitri,
                $templateId,
                [
                    '1' => $key1, // Menggunakan key '1' untuk parameter pertama {{1}}
                    '2' => $key2  // Menggunakan key '2' untuk parameter kedua {{2}}
                ],
                'Bu Fitri' // Menambahkan nama penerima untuk parameter to_name
            );

            $agenda->status_kirim = 'terkirim';
            $agenda->waktu_kirim = now();
            $agenda->save();
            
            // Return JSON response jika request AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim ke Bu Fitri via Qontak!',
                    'status' => 'terkirim'
                ]);
            }
            
            return redirect()->back()->with('success', 'Pesan berhasil dikirim ke Bu Fitri via Qontak!');
        } catch (\Exception $e) {
            $agenda->status_kirim = 'gagal';
            $agenda->waktu_kirim = now();
            $agenda->save();
            \Log::error('Gagal mengirim pesan agenda harian via Qontak: ' . $e->getMessage());
            
            // Return JSON response jika request AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesan gagal dikirim: ' . $e->getMessage(),
                    'status' => 'gagal'
                ]);
            }
            
            return redirect()->back()->with('error', 'Pesan gagal dikirim: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan formulir untuk mengedit agenda harian.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $agenda = AgendaHarian::findOrFail($id);
        return view('admin.send_message.edit', compact('agenda'));
    }

    /**
     * Memperbarui agenda harian di database.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $agenda = AgendaHarian::findOrFail($id);
        $validated = $request->validate([
            'ootd_cewek' => 'nullable|string|max:255',
            'ootd_cowok' => 'nullable|string|max:255',
            'agenda_manual' => 'nullable|array',
            'agenda_manual.*.jam' => 'nullable|string|max:50',
            'agenda_manual.*.jam_selesai' => 'nullable|string|max:50',
            'agenda_manual.*.judul' => 'nullable|string|max:500',
            'agenda_manual.*.lokasi' => 'nullable|string|max:255',
        ]);
        $agenda->ootd_cewek = $validated['ootd_cewek'] ?? $agenda->ootd_cewek;
        $agenda->ootd_cowok = $validated['ootd_cowok'] ?? $agenda->ootd_cowok;
        $agenda->status_kirim = 'belum';
        $agenda->waktu_kirim = null;
        $agenda->save();
        // Hapus agenda manual lama
        $agenda->agendaManuals()->delete();
        // Simpan agenda manual baru
        if (isset($validated['agenda_manual'])) {
            foreach ($validated['agenda_manual'] as $item) {
                if (!empty($item['jam']) && !empty($item['judul'])) {
                    $agenda->agendaManuals()->create([
                        'jam' => $item['jam'],
                        'jam_selesai' => $item['jam_selesai'] ?? null,
                        'judul' => $item['judul'],
                        'lokasi' => $item['lokasi'] ?? null,
                    ]);
                }
            }
        }
        return redirect()->route('admin.send_message.show', $agenda->id)
            ->with('success', 'Agenda harian berhasil diupdate!');
    }

    /**
     * Menghapus agenda harian dari database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $agenda = AgendaHarian::findOrFail($id);
        $agenda->delete();
        return redirect()->route('admin.send_message.index')->with('success', 'Agenda harian berhasil dihapus!');
    }
}
