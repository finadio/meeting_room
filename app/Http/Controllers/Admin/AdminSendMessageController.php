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
                $bulanMap = [
                    'januari'=>1,'jan'=>1,'februari'=>2,'feb'=>2,'maret'=>3,'mar'=>3,'april'=>4,'apr'=>4,
                    'mei'=>5,'juni'=>6,'jun'=>6,'juli'=>7,'jul'=>7,'agustus'=>8,'agu'=>8,'september'=>9,'sep'=>9,
                    'oktober'=>10,'okt'=>10,'november'=>11,'nov'=>11,'desember'=>12,'des'=>12,
                    'january'=>1,'february'=>2,'march'=>3,'april'=>4,'may'=>5,'june'=>6,'july'=>7,'august'=>8,'september'=>9,'october'=>10,'november'=>11,'december'=>12
                ];
                $qLower = strtolower($q);
                $bulan = null; $tahun = null;
                foreach ($bulanMap as $nama=>$num) {
                    if (preg_match("/\b$nama\b/i", $qLower)) {
                        $bulan = $num;
                        break;
                    }
                }
                if ($bulan) {
                    // Cek jika ada tahun di q
                    if (preg_match('/\b(19|20)\\d{2}\b/', $qLower, $match)) {
                        $tahun = $match[0];
                    }
                    if ($tahun) {
                        $sub->orWhereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                    } else {
                        $sub->orWhereMonth('tanggal', $bulan);
                    }
                }
            });
        }
        // Filter periode
        if ($periode === 'minggu') {
            $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($periode === 'bulan') {
            $query->whereBetween('tanggal', [now()->startOfMonth(), now()->endOfMonth()]);
        }
        // Urutkan
        $query->orderBy('tanggal', $sort);
        // Limit default 14 jika all, jika filter minggu/bulan tampilkan semua
        // if ($periode === 'all') {
        //     $query->limit(14);
        // }
        $agendas = $query->paginate(10)->appends($request->except('page'));
        $bookings = Booking::whereBetween('booking_date', [now()->subDays(1)->toDateString(), now()->addDays(14)->toDateString()])
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
        $validated = $request->validate([
            'ootd' => 'required|array',
            'ootd.*.date' => 'required|date',
            'ootd.*.cewek' => 'nullable|string|max:255',
            'ootd.*.cowok' => 'nullable|string|max:255',
            'agenda' => 'nullable|array',
            'agenda.*.jam' => 'nullable|string|max:50',
            'agenda.*.judul' => 'nullable|string|max:500',
            'agenda.*.lokasi' => 'nullable|string|max:255',
        ]);

        $ootd = $validated['ootd'];
        $agenda = $request->input('agenda', []);

        // Proses per tanggal
        foreach ($ootd as $i => $row) {
            $tanggal = $row['date'];
            $ootd_cewek = $row['cewek'] ?? null;
            $ootd_cowok = $row['cowok'] ?? null;

            // Ambil agenda manual untuk tanggal ini
            $agenda_per_tanggal = collect($agenda)->filter(function ($item) use ($tanggal) {
                return isset($item['date']) && $item['date'] === $tanggal;
            })->values()->all();

            // Simpan atau update agenda_harian
            AgendaHarian::updateOrCreate(
                ['tanggal' => $tanggal],
                [
                    'ootd_cewek' => $ootd_cewek,
                    'ootd_cowok' => $ootd_cowok,
                    'agenda_manual' => $agenda_per_tanggal,
                    'status_kirim' => 'belum', // Reset status kirim jika diupdate
                ]
            );
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
        $messageLines = [];

        // Agenda Booking
        if ($bookings->isNotEmpty()) {
            $messageLines[] = "*Agenda Booking:*";
            foreach ($bookings as $booking) {
                $messageLines[] = "- " . $booking->booking_time . " - " . $booking->booking_end . " | " .
                                  ($booking->meeting_title ?? 'Tanpa Judul') . " | " .
                                  ($booking->facility->name ?? 'Ruangan Tidak Diketahui') . " | " .
                                  ($booking->user->name ?? 'Pemesan Tidak Diketahui');
            }
            $messageLines[] = ""; // Baris kosong untuk pemisah
        }

        // Agenda Manual
        if ($agenda->agenda_manual && count($agenda->agenda_manual) > 0) {
            $messageLines[] = "*Agenda Manual:*";
            foreach ($agenda->agenda_manual as $item) {
                $messageLines[] = "- " . ($item['jam'] ?? '-') . " | " .
                                  ($item['judul'] ?? '-') .
                                  (!empty($item['lokasi']) ? " | " . $item['lokasi'] : '');
            }
            $messageLines[] = ""; // Baris kosong untuk pemisah
        }

        // OOTD
        $ootdCewek = $agenda->ootd_cewek ?? '-';
        $ootdCowok = $agenda->ootd_cowok ?? '-';
        $messageLines[] = "OOTD Cewek: " . $ootdCewek;
        $messageLines[] = "OOTD Cowok: " . $ootdCowok;

        if ($singleLine) {
            // Gabungkan semua baris dengan separator " | ", hilangkan baris kosong
            $messageLines = array_filter($messageLines, function($line) {
                return trim($line) !== '';
            });
            return implode(' | ', $messageLines);
        } else {
            return implode("\n", $messageLines);
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
        $agenda = AgendaHarian::findOrFail($id);
        $bookings = Booking::where('booking_date', $agenda->tanggal->toDateString())
            ->with(['facility', 'user']) // Eager loading untuk performa
            ->orderBy('booking_time')
            ->get();

        // Parameter 1: Hari, Tanggal untuk template Qontak
        $key1 = $agenda->tanggal->isoFormat('dddd, D MMMM Y');

        // Parameter 2: Isi Pesan (agenda detail) untuk template Qontak
        $key2 = $this->generateMessageBody($agenda, $bookings, true); // Untuk Qontak (single line)

        // Untuk preview di blade, gunakan multiline agar tidak dobel OOTD
        $previewBody = $this->generateMessageBody($agenda, $bookings, false); // multiline
        $pesanPreview = "Selamat pagi teman - teman, selamat mengawali hari ini penuh rasa syukur dan sehat selalu, adapun agenda dihari *" . $key1 . "* sbb:\n\n" . $previewBody . "\n\nTerima Kasih 🙏 \nTuhan memberkati 😇 \nBisa-Harus Bisa-Pasti Bisa \n#KolaborasiDalamHarmoni 🤲🙏😇💪💪🔥🔥";

        return view('admin.send_message.show', compact('agenda', 'bookings', 'pesanPreview'));
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
        $agenda = AgendaHarian::findOrFail($id);
        $bookings = Booking::where('booking_date', $agenda->tanggal->toDateString())
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
            'agenda_manual.*.judul' => 'nullable|string|max:500',
            'agenda_manual.*.lokasi' => 'nullable|string|max:255',
        ]);
        $agenda->ootd_cewek = $validated['ootd_cewek'] ?? $agenda->ootd_cewek;
        $agenda->ootd_cowok = $validated['ootd_cowok'] ?? $agenda->ootd_cowok;
        $agenda->agenda_manual = $validated['agenda_manual'] ?? $agenda->agenda_manual;
        $agenda->save();
        return redirect()->route('admin.send_message.index')->with('success', 'Agenda harian berhasil diupdate!');
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
