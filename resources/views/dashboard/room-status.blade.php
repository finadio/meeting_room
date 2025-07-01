<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Ruangan</title>
    <!-- Favicon utama -->
    <link rel="icon" href="{{ asset('bookmeet.png') }}" type="image/x-icon">

    <!-- Favicon untuk layar retina (misalnya 32x32, 64x64) -->
    <link rel="icon" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" sizes="64x64" href="{{ asset('favicon-64x64.png') }}">

    <!-- Ikon untuk aplikasi web (Web App) -->
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Link ke CSS Anda -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }

        hr {
            border: 0;
            height: 2px;
            background: #ddd; /* Warna abu-abu */
            margin: 20px 0;
        }

        .facility-table {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9; /* Warna latar belakang lembut */
        }

        .container {
            padding: 20px;
        }
        .table {
            margin: 0 auto;
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        
        .text-left {
            text-align: left;
        }

        .table thead th {
            background-color: #001f3f;
            color: white;
        }

        .table-secondary {
           background-color: #d6d8db;
           color: #212529;
        }

        .table-success {
            background-color: #d4edda;
            color: #155724;
        }

        .table-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .legend {
            font-size: 16px;    /* Ukuran font untuk keseluruhan legend */
            padding: 20px;      /* Padding di sekitar legend */
            margin-top: 20px;   /* Jarak atas */
            max-width: 500px;   /* Maksimal lebar legend */
            margin-left: auto;  /* Rata tengah legend */
            margin-right: auto; /* Rata tengah legend */
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px; /* Menambah jarak antara item legend */
        }

        .legend-color {
            width: 20px;         /* Ukuran kotak warna */
            height: 20px;        /* Ukuran kotak warna */
            margin-right: 12px;  /* Jarak antara kotak warna dan teks */
        }

        .legend-success {
            background-color: #d4edda;
        }

        .legend-secondary {
            background-color: #d6d8db;
        }
        .legend-warning {
            background-color: #fff3cd;
            border: 1px solid #ddd;
        }
        .legend-default {
            background-color: #ffffff;
            border: 1px solid #ddd;
        }
        .legend-item span {
            font-size: 10px;      /* Ukuran teks legend */
        }
        .text-danger {
            color: red !important;
            font-weight: bold;
        }

        /* Menyesuaikan desain untuk layar kecil */
        @media (max-width: 768px) {
            .table {
                width: 100%;
                font-size: 12px; /* Ukuran font lebih kecil di layar kecil */
            }
            .container {
                padding: 10px;
            }
        }

        img.maskot {
            transition: transform 0.3s, opacity 0.3s;
        }

        img.maskot:hover {
            transform: scale(1.1);
            opacity: 1;
        }
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
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Warna hitam transparan */
            z-index: -1; /* Di belakang konten */
        }

        .status-box {
            border: 2px solid #333; /* Border kotak */
            padding: 20px;
            margin: 20px;
            background-color: #f9f9f9; /* Warna latar belakang kotak */
            border-radius: 10px; /* Sudut kotak melengkung */
        }

        .status-title {
            text-align: center;
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        .legend-box {
            border-top: 2px solid #ddd; /* Garis pemisah di bawah judul */
            padding-top: 10px;
            margin-top: 20px;
        }

        .legend-box legend {
            font-size: 18px;
            font-weight: bold;
        }

        .legend-box ul {
            list-style: none;
            padding: 0;
        }

        .legend-box ul li {
            margin: 5px 0;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <style>
            body {
                background-color: #f0f8ff; /* Ganti dengan warna favorit, misalnya biru muda */
                /* background-image: url('/img/global.jpg'); */ /* Path gambar */
                /* background-size: cover;
                background-repeat: no-repeat;
                background-position: center center; */
            }

        </style>
        <div class="status-box">
            <h3>Jadwal Pemakaian Ruangan</h3>
            <!-- <hr class="my-4"> -->
            {{-- @if($bookings->isNotEmpty()) --}}
                {{-- @foreach($bookings as $facilityId => $facilityBookings) --}}
            @if(!empty($bookings))
                @foreach($bookings as $facilityId => $facilityBookings)
                    <div class="facility-table mb-5">
                        <h5>{{ $facilityBookings->first()->facility->name }}</h5>
                        <p>Tanggal Booking: {{ $facilityBookings->first()->booking_date->format('d-m-Y') }}</p>
                        <div class="table-responsive">  
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Status</th>
                                        <th>Pemakai</th>
                                        <th>Judul Meeting</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($facilityBookings as $booking)
                                        @php
                                        $currentTime = now()->setTimezone('Asia/Jakarta');
                                        
                                        if (!empty($booking->booking_time) && !empty($booking->booking_end)) {
                                            $bookingStartTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time);
                                            $bookingEndTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_end);
                                        } else {
                                            $bookingStartTime = null;
                                            $bookingEndTime = null;
                                        }
                                        
                                        if ($booking->check_out) {
                                            $status = 'Selesai';
                                        } elseif (!$booking->check_out && $bookingEndTime->isPast()) {
                                            $status = 'Extend';
                                        } elseif ($booking->check_in && $currentTime->lessThanOrEqualTo($bookingEndTime)) {
                                            $status = 'On Progress';
                                        } else {
                                            $status = 'On Schedule';
                                        }

                                        $rowClass = '';
                                        if ($status == 'Extend') {
                                            $rowClass = 'table-warning';
                                        } elseif ($status == 'Selesai') {
                                            $rowClass = 'table-secondary';
                                        } elseif ($currentTime->between($bookingStartTime, $bookingEndTime)) {
                                            $rowClass = 'table-success';
                                        }
                                        @endphp
                                        
                                        <tr class="{{ $rowClass }}">
                                            <td>{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</td> <!-- Jam Mulai -->
                                            <td>{{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}</td> <!-- Jam Selesai -->
                                            <td><span class="{{ $status == 'On Progress' ? 'text-danger fw-bold' : '' }}">
                                                {{ $status }}
                                                </span>
                                            </td>
                                            <td>{{ $booking->group_name }}</td>
                                            <td>{{ $booking->meeting_title }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> 
                    </div>  
                    <!-- Garis Pemisah Antar Tabel -->
                    <!-- @if (!$loop->last)
                        <hr class="my-4">
                    @endif -->
                @endforeach
                <!-- Legend for color meanings -->
                <div class="legend text-left">
                    <h6>Keterangan Warna:</h6>
                    <!-- Legend Horizontal -->
                    <div class="d-flex justify-content-start align-items-center mt-3">
                        <div class="legend-item">
                            <div class="legend-color legend-default"></div>
                            <span>Putih: Belum mulai</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color legend-success"></div>
                            <span>Hijau Muda: Sedang berlangsung</span>
                            <!-- <span>Sedang berlangsung</span> -->
                            
                        </div>
                        <div class="legend-item">
                            <div class="legend-color legend-secondary"></div>
                            <span>Abu-abu: Selesai</span>
                        </div>
                        
                        <div class="legend-item">
                            <div class="legend-color legend-warning"></div>
                            <span>Kuning: Extend waktu</span>
                        </div>
                    </div>    
                </div>
            @else
                <p>Tidak ada booking untuk hari ini.</p>
            @endif
        </div>
            <!-- Tambahkan running text -->
        <div class="running-text">
            <span>#AndaTidakSendiri ==> BPR MSA terdaftar dan diawasi oleh OJK, serta merupakan peserta penjaminan LPS. <== #ImajinasiTakBertepi</span>
        </div>

        <!-- Tambahkan maskot di sudut kanan bawah -->
        <!-- <img src="{{ asset('img/shaka_utama.png') }}" 
            alt="Maskot MSA" 
            class="maskot"
            style="position: fixed; bottom: 10px; right: 10px; width: 100px; opacity: 0.8; z-index: 999;"> -->
    </div>
    
    <script> 
        // Fungsi untuk refresh data secara otomatis setiap 5 menit
         setInterval(() => {
            window.location.reload();
         }, 30000); // 300000 ms = 5 menit

    </script>     
</body>
</html>
