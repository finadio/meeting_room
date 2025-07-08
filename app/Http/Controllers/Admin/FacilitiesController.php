<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility; // Pastikan ini diimpor
use App\Models\Notification; // Pastikan ini diimpor
use Illuminate\Support\Facades\Storage; // Pastikan ini diimpor
use Illuminate\Database\QueryException; // Pastikan ini diimpor
use Illuminate\Http\Request; // Pastikan ini diimpor
use Illuminate\Support\Facades\Auth; // Pastikan ini diimpor jika auth()->id() digunakan

class FacilitiesController extends Controller
{
    public function index(Request $request) // Tambahkan Request $request karena sudah dipakai di search
    {
        $searchQuery = $request->input('search'); // Ambil input pencarian
        
        $facilities = Facility::query() // Gunakan query() untuk memulai builder
            ->when($searchQuery, function ($query, $searchQuery) {
                // Terapkan filter pencarian jika ada searchQuery
                $query->where('name', 'like', '%' . $searchQuery . '%')
                      ->orWhere('description', 'like', '%' . $searchQuery . '%')
                      ->orWhere('location', 'like', '%' . $searchQuery . '%')
                      ->orWhere('facility_type', 'like', '%' . $searchQuery . '%')
                      ->orWhere('contact_person', 'like', '%' . $searchQuery . '%')
                      ->orWhere('contact_email', 'like', '%' . $searchQuery . '%')
                      ->orWhere('contact_phone', 'like', '%' . $searchQuery . '%');
            })
            ->paginate(10); // Lanjutkan dengan paginasi

        $unreadNotificationCount = Notification::where('is_read', false)->count();
        return view('admin.facilities.index', compact('facilities', 'unreadNotificationCount', 'searchQuery')); // Teruskan searchQuery ke view
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'map_coordinates' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk input 'image'
                'price_per_hour' => 'nullable|numeric|min:0',
                'facility_type' => 'nullable|string|max:255',
                'opening_time' => 'nullable|string|max:255',
                'closing_time' => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'contact_email' => 'nullable|string|email|max:255',
                'contact_phone' => 'nullable|string|max:255',
            ]);

            $facilityData = $request->except('image'); // Ambil semua data kecuali objek file 'image'

            // Tangani upload gambar
            if ($request->hasFile('image')) { // Periksa input file bernama 'image'
                $imagePath = $request->file('image')->store('facility_images', 'public');
                $facilityData['image_path'] = 'storage/' . $imagePath; // Simpan jalur yang benar ke kolom 'image_path'
            } else {
                $facilityData['image_path'] = null; // Pastikan image_path null jika tidak ada file diunggah
            }

            $facilityData['added_by'] = Auth::id(); // Menggunakan Auth::id()

            Facility::create($facilityData);

            return redirect()->route('admin.facilities.index')->with('success', 'Facility Added Successfully.');
        } catch (QueryException $e) {
            // Tangani pengecualian, catat, atau kembalikan respons error
            return redirect()->back()->with('error', 'Error creating facility: ' . $e->getMessage())->withInput(); // Tambahkan withInput()
        }
    }

    public function show(Facility $facility)
    {
        return view('admin.facilities.show', compact('facility'));
    }

    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                // 'map_coordinates' => 'nullable|string|max:255', // Komentar ini karena tidak ada di validasi Anda
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk input 'image'
                // 'price_per_hour' => 'nullable|numeric|min:0', // Komentar ini
                // 'facility_type' => 'nullable|string|max:255', // Komentar ini
                'opening_time' => 'nullable|string|max:255',
                'closing_time' => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'contact_email' => 'nullable|string|email|max:255',
                'contact_phone' => 'nullable|string|max:255',
            ]);

            $facility = Facility::findOrFail($id);
            $facilityData = $request->except('image'); // Ambil semua data kecuali objek file 'image'

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada dan valid
                if (!is_null($facility->image_path) && Storage::disk('public')->exists(str_replace('storage/', '', $facility->image_path))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $facility->image_path));
                }
                
                $imagePath = $request->file('image')->store('facility_images', 'public');
                $facilityData['image_path'] = 'storage/' . $imagePath; // Simpan jalur yang benar
            }
            // Jika tidak ada file baru diunggah, dan tidak ada 'image_path' dari request (misal input hidden),
            // maka image_path yang lama akan tetap ada di $facility->image_path (tidak diubah oleh $request->except())
            // Jika Anda ingin menghapus gambar jika input file kosong, Anda perlu logika tambahan.

            $facility->update($facilityData);

            return redirect()->route('admin.facilities.index')->with('success', 'Facility Updated Successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Error updating facility: ' . $e->getMessage())->withInput(); // Tambahkan withInput()
        }
    }

    public function destroy(Facility $facility)
    {
        // Hapus juga gambar terkait jika ada
        if (!is_null($facility->image_path) && Storage::disk('public')->exists(str_replace('storage/', '', $facility->image_path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $facility->image_path));
        }
        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', 'Facility Deleted Successfully.');
    }
}