

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Ruangan</title>
    <link rel="icon" href="{{ asset('bookmeet.png') }}" type="image/x-icon">

    <link rel="icon" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" sizes="64x64" href="{{ asset('favicon-64x64.png') }}">

    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif; /* Pastikan Poppins diterapkan */
            background-color: #eef2f6; /* Latar belakang abu-abu sangat muda */
            padding: 20px;
        }

        .status-container {
            background: #ffffff; /* Latar belakang putih bersih */
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08); /* Bayangan lembut */
            padding: 25px; /* Padding sedikit lebih kecil */
            margin-bottom: 20px;
            overflow-x: hidden; /* Pastikan tidak ada scroll horizontal di container utama */
        }

        /* Header Baru (Logo & Judul Terintegrasi) - Sangat Ramping */
        .status-header {
            display: flex;
            align-items: center;
            justify-content: center; /* Pusatkan logo dan judul */
            margin-bottom: 15px; /* Jarak lebih kecil */
            padding-bottom: 10px; /* Padding lebih kecil */
            border-bottom: 1px solid #f0f2f5;
        }

        .status-header img {
            max-height: 40px; /* Ukuran logo sangat kecil */
            width: auto;
            margin-right: 10px;
        }

        .status-header h1 {
            color: #1e3c72; /* Warna biru gelap */
            font-size: 1.8rem; /* Ukuran judul utama */
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.08);
        }

        .legend-section {
            text-align: center;
            margin-bottom: 30px; /* Jarak lebih kecil dari tabel */
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f2f5;
        }

        .legend-section h6 {
            color: #1e3c72;
            font-size: 1rem; /* Font legend lebih kecil */
            font-weight: 600;
            margin-bottom: 12px;
        }

        .legend-items {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 25px;
            align-items: center;
            font-size: 0.95rem;
            color: #555;
        }

        .legend-item {
            display: flex;
            align-items: center;
        }

        .legend-color {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            margin-right: 8px;
            border: 1px solid #ccc;
        }

        /* Warna legend */
        .legend-default { background-color: #fcfdfe; }
        .legend-success { background-color: #d4edda; border-color: #c3e6cb; }
        .legend-secondary { background-color: #e9ecef; border-color: #dae0e5; }
        .legend-warning { background-color: #fff3cd; border-color: #ffeeba; }

        .facility-layout {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Jarak antar kartu fasilitas */
            justify-content: center;
            align-items: stretch; /* Kunci: Membuat tinggi kartu sama */
        }

        .facility-layout > div {
            flex: 1 1 calc(50% - 10px); /* 2 kolom di layar lebar */
            max-width: calc(50% - 10px);
            box-sizing: border-box;
        }
        
        @media (max-width: 991.98px) {
            .facility-layout > div {
                flex: 1 1 100%; /* 1 kolom di layar tablet/mobile */
                max-width: 100%;
            }
        }

        .facility-card {
            background-color: #ffffff; /* Latar belakang putih */
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px; /* Padding lebih kecil */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); /* Bayangan lebih tipis */
            height: 100%; /* Penting untuk align-items: stretch */
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Konten dimulai dari atas */
        }

        .facility-card h5 {
            color: #1e3c72; /* Warna biru gelap yang konsisten */
            font-size: 1.2rem; /* Ukuran judul fasilitas lebih kecil */
            font-weight: 600;
            margin-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 5px;
        }

        .facility-card p {
            color: #6c757d;
            margin-bottom: 10px;
            font-size: 0.85rem;
        }

        /* Wadah untuk tabel agar bisa di-scroll vertikal internal */
        .table-wrapper { 
            max-height: 300px; /* Tinggi maksimal sebelum scroll muncul */
            overflow-y: auto; /* Aktifkan scroll vertikal */
            margin-bottom: 0;
            border: 1px solid #e9ecef; 
            border-radius: 8px;
            flex-grow: 1; /* Agar wrapper mengisi sisa ruang di facility-card */
            display: flex; /* Agar tabel di dalamnya bisa mengisi tinggi wrapper */
            flex-direction: column;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 0.75rem; /* Ukuran font tabel sangat kecil untuk muat banyak */
            table-layout: auto; /* Biarkan browser menyesuaikan lebar kolom */
            min-width: fit-content; /* Pastikan tabel tidak terlalu menyusut */
        }

        .table th,
        .table td {
            padding: 5px 3px; /* Padding sangat ramping */
            border: 1px solid #e9ecef;
            text-align: left;
            word-wrap: break-word; /* Mengizinkan teks membungkus */
            overflow: visible; /* Penting: tidak menyembunyikan atau memotong teks */
            text-overflow: clip; /* Pastikan tidak ada ellipsis */
        }
        
        /* Hapus pengaturan lebar kolom tetap agar lebih fleksibel */
        .table th:nth-child(n), .table td:nth-child(n) { width: auto; }


        .table thead th {
            background-color: #e0e7ff; /* Warna header tabel */
            color: #1e3c72;
            font-weight: 700;
            border-bottom: 2px solid #c3dafe;
            font-size: 0.7em; /* Font header lebih kecil */
            text-transform: uppercase;
        }
        
        .table tbody tr {
            background-color: #ffffff !important; /* Latar belakang baris putih bersih */
        }
        .table tbody tr:nth-of-type(even) { /* Pastikan baris genap juga putih */
            background-color: #ffffff !important; 
        }

        /* Warna baris status */
        .table-success { background-color: #e6ffed !important; color: #0f5132 !important; }
        .table-secondary { background-color: #e2e3e5 !important; color: #41464b !important; }
        .table-warning { background-color: #fffacd !important; color: #664d03 !important; }

        .fw-bold { font-weight: bold; }

        .running-text {
            font-size: 14px;
            color: #fff;
            background-color: #001f3f;
            padding: 10px;
            white-space: nowrap;
            overflow: hidden;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .running-text span {
            display: inline-block;
            padding-left: 100%;
            animation: scroll-text 15s linear infinite;
        }

        @keyframes scroll-text {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        
        /* Responsive adjustments for smaller screens */
        @media (max-width: 768px) {
            .status-header h1 {
                font-size: 1.8rem;
            }
            .status-header img {
                max-height: 40px;
            }
            .legend-items {
                gap: 15px;
                font-size: 0.85rem;
            }
            .legend-color {
                width: 14px;
                height: 14px;
            }
            .facility-card h5 {
                font-size: 1.1rem;
            }
            .table th, .table td {
                font-size: 0.7rem;
                padding: 6px 3px;
            }
            /* Menyesuaikan tinggi wrapper tabel untuk mobile */
            .table-wrapper {
                max-height: 250px; /* Lebih pendek di mobile agar muat */
            }
        }

        @media (max-width: 576px) {
            .status-header {
                flex-direction: column;
                gap: 10px;
            }
            .status-header img {
                margin-right: 0;
            }
            .status-header h1 {
                font-size: 1.5rem;
            }
            .legend-items {
                flex-direction: column;
                gap: 8px;
                align-items: flex-start;
            }
            .table th, .table td {
                font-size: 0.65rem;
                padding: 4px 2px;
            }
            .table-wrapper {
                max-height: 200px; /* Lebih pendek lagi di mobile kecil */
            }
        }
    </style>
</head>
<body>
    <div class="status-container"> {{-- Menggunakan container-fluid untuk lebar penuh --}}
        <div class="status-header">
            <img src="{{ asset('img/msa.png') }}" alt="Logo BPR MSA">
            <h1>Status Pemakaian Ruangan</h1>
        </div>

        <div class="legend-section">
            <h6>Keterangan Warna:</h6>
            <div class="legend-items">
                <div class="legend-item">
                    <div class="legend-color legend-default"></div>
                    <span>Belum Mulai</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-success"></div>
                    <span>Sedang Berlangsung</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-secondary"></div>
                    <span>Selesai</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-warning"></div>
                    <span>Extend Waktu</span>
                </div>
            </div>
        </div>

        @if(!empty($bookings) && $bookings->count() > 0)
            @php
                // Variabel $bookings sudah dikelompokkan berdasarkan facility_id dari controller
                // Jadi, $item dalam closure sort() adalah koleksi booking untuk satu fasilitas.
                $sortedFacilities = $bookings->sort(function ($bookingsCollectionForFacility1, $bookingsCollectionForFacility2) {
                    // Ambil nama fasilitas dari booking pertama di setiap sub-koleksi
                    $name1 = optional($bookingsCollectionForFacility1->first())->facility->name;
                    $name2 = optional($bookingsCollectionForFacility2->first())->facility->name;

                    // Urutan prioritas
                    $order = ['Ruang Meeting Lobby', 'Ruang Meeting Lantai 2'];
                    $pos1 = array_search($name1, $order);
                    $pos2 = array_search($name2, $order);

                    if ($pos1 !== false && $pos2 !== false) {
                        return $pos1 - $pos2;
                    } elseif ($pos1 !== false) {
                        return -1; // name1 ada di order, name2 tidak
                    } elseif ($pos2 !== false) {
                        return 1; // name2 ada di order, name1 tidak
                    } else {
                        return strcmp($name1, $name2); // Urutan alfabetis untuk lainnya
                    }
                });
            @endphp

            <div class="row facility-layout"> {{-- Menggunakan row untuk Bootstrap columns --}}
                @foreach($sortedFacilities as $facilityId => $facilityBookings)
                    <div class="col-lg-6 mb-4"> {{-- Gunakan col-lg-6 untuk 2 kolom di layar besar --}}
                        <div class="facility-card">
                            <h5>{{ $facilityBookings->first()->facility->name }}</h5>
                            <p>Tanggal Booking: {{ optional($facilityBookings->first())->booking_date ? \Carbon\Carbon::parse($facilityBookings->first()->booking_date)->format('d F Y') : '-' }}</p>
                            
                            {{-- Wrapper baru untuk tabel agar bisa di-scroll vertikal (kompromi) --}}
                            <div class="table-wrapper"> 
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Jam Mulai</th>
                                            <th>Jam Selesai</th>
                                            <th>Status</th>
                                            <th>Pemakai</th>
                                            <th>Judul Meeting</th>
                                            <th>Keterangan</th> {{-- Kolom keterangan --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($facilityBookings as $booking)
                                            @php
                                                $rowClass = '';
                                                if ($booking->room_status === 'Selesai') {
                                                    $rowClass = 'table-secondary';
                                                } elseif ($booking->room_status === 'Sedang Berlangsung') {
                                                    $rowClass = 'table-success';
                                                } elseif ($booking->room_status === 'Extend Waktu') {
                                                    $rowClass = 'table-warning';
                                                }
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td>{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}</td>
                                                <td><span class="{{ ($booking->room_status == 'Sedang Berlangsung' || $booking->room_status == 'Extend Waktu') ? 'fw-bold' : '' }}">{{ $booking->room_status }}</span></td>
                                                <td>{{ $booking->group_name }}</td>
                                                <td>{{ $booking->meeting_title }}</td>
                                                <td>{{ $booking->description ?? '-' }}</td> {{-- Tampilkan keterangan jika ada --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center">Tidak ada booking untuk hari ini.</p>
        @endif
    </div>

    <div class="running-text">
        <span>#AndaTidakSendiri ==> BPR MSA terdaftar dan diawasi oleh OJK, serta merupakan peserta penjaminan LPS. <== #ImajinasiTakBertepi</span>
    </div>

    <script>
        setInterval(() => {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>
