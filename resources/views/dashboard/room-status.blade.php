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
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc; /* Latar belakang senada dengan admin dashboard */
            text-align: center;
            padding: 20px;
        }

        .status-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
        }

        .status-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradient-flow 3s ease infinite;
        }

        @keyframes gradient-flow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .status-box h3 {
            color: #1e3c72; /* Dark blue for title */
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .facility-card-wrapper {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            padding: 20px;
            margin-bottom: 20px;
            text-align: left;
            height: calc(100% - 20px); /* Adjust height to fill column */
            display: flex;
            flex-direction: column;
        }
        
        .facility-card-wrapper h5 {
            color: #2a5298; /* Medium blue for facility name */
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .facility-card-wrapper p {
            color: #6c757d; /* Gray for date */
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 5px; /* Reduced spacing */
            margin-bottom: 0;
            font-size: 0.85rem; /* Slightly smaller font */
        }

        .modern-table thead th {
            background: linear-gradient(135deg, #e0e7ff 0%, #c3dafe 100%);
            color: #1e3c72;
            padding: 10px 8px; /* Reduced padding */
            font-weight: 700;
            text-align: left;
            border-bottom: none;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 0.8em; /* Smaller font for headers */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 6px; /* Smaller border radius for table headers */
        }

        .modern-table tbody tr {
            background: #fdfefe;
            border-radius: 8px; /* Smaller border radius for rows */
            transition: all 0.3s ease;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05); /* Lighter shadow */
        }

        .modern-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .modern-table tbody td {
            padding: 10px 8px; /* Reduced padding */
            vertical-align: middle;
            border-top: none;
            font-size: 0.85em; /* Match header font size */
            color: #333;
            border-radius: 6px; /* Apply border radius to cells */
        }
        
        /* Specific row colors */
        .table-secondary { background-color: #e9ecef !important; color: #495057 !important; } /* Selesai */
        .table-success { background-color: #d4edda !important; color: #155724 !important; } /* On Progress */
        .table-warning { background-color: #fff3cd !important; color: #856404 !important; } /* Extend */
        
        .table-success .fw-bold { /* Override for 'On Progress' status text */
            color: #155724 !important; /* Ensure readable dark green */
        }
        .table-warning .fw-bold { /* Override for 'Extend' status text */
            color: #856404 !important; /* Ensure readable dark yellow */
        }
        .table-secondary .fw-bold { /* Override for 'Selesai' status text */
            color: #495057 !important; /* Ensure readable dark gray */
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .legend-section {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            text-align: center;
        }

        .legend-section h6 {
            color: #1e3c72;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .legend-items {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px; /* Space between legend items */
        }

        .legend-item {
            display: flex;
            align-items: center;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 8px;
            border: 1px solid #ddd;
        }

        /* Legend specific colors (matching table row backgrounds) */
        .legend-default { background-color: #ffffff; }
        .legend-success { background-color: #d4edda; }
        .legend-secondary { background-color: #e9ecef; }
        .legend-warning { background-color: #fff3cd; }

        .legend-item span {
            color: #4a5568;
            font-size: 0.9rem;
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
        /* Responsive adjustments */
        @media (max-width: 991px) {
            .facility-card-wrapper {
                height: auto; /* Allow height to adjust on smaller screens */
            }
        }

        @media (max-width: 767px) {
            .status-box {
                padding: 20px;
                border-radius: 12px;
            }
            .status-box h3 {
                font-size: 1.5rem;
                margin-bottom: 15px;
            }
            .facility-card-wrapper {
                padding: 15px;
            }
            .facility-card-wrapper h5 {
                font-size: 1.1rem;
            }
            .facility-card-wrapper p {
                font-size: 0.8rem;
            }
            .modern-table {
                font-size: 0.75rem;
            }
            .modern-table thead th, .modern-table tbody td {
                padding: 8px 5px;
                font-size: 0.75em;
            }
            .legend-items {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="status-box">
            <h3>Jadwal Pemakaian Ruangan</h3>
            
            @if(!empty($bookings) && $bookings->count() > 0)
                <div class="row">
                    @foreach($bookings as $facilityId => $facilityBookings)
                        <div class="col-lg-6 col-md-6 mb-4">
                            <div class="facility-card-wrapper">
                                <h5>{{ $facilityBookings->first()->facility->name }}</h5>
                                <p>Tanggal Booking: {{ \Carbon\Carbon::parse($facilityBookings->first()->booking_date)->format('d F Y') }}</p>
                                <div class="table-responsive">  
                                    <table class="table modern-table">
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
                                                
                                                $status = 'On Schedule';
                                                $rowClass = '';

                                                if ($booking->check_out) {
                                                    $status = 'Selesai';
                                                    $rowClass = 'table-secondary';
                                                } elseif ($bookingStartTime && $bookingEndTime) {
                                                    if ($currentTime->greaterThan($bookingEndTime) && !$booking->check_out) {
                                                        $status = 'Extend';
                                                        $rowClass = 'table-warning';
                                                    } elseif ($currentTime->between($bookingStartTime, $bookingEndTime) && $booking->check_in) {
                                                        $status = 'On Progress';
                                                        $rowClass = 'table-success';
                                                    }
                                                }
                                                @endphp
                                                
                                                <tr class="{{ $rowClass }}">
                                                    <td>{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}</td>
                                                    <td>
                                                        <span class="{{ ($status == 'On Progress' || $status == 'Extend') ? 'fw-bold' : '' }}">
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
                        </div>  
                    @endforeach
                </div>
                <div class="legend-section">
                    <h6>Keterangan Warna:</h6>
                    <div class="legend-items">
                        <div class="legend-item">
                            <div class="legend-color legend-default"></div>
                            <span>Putih: Belum mulai</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color legend-success"></div>
                            <span>Hijau Muda: Sedang berlangsung</span>
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
        <div class="running-text">
            <span>#AndaTidakSendiri ==> BPR MSA terdaftar dan diawasi oleh OJK, serta merupakan peserta penjaminan LPS. <== #ImajinasiTakBertepi</span>
        </div>
    </div>
    
    <script> 
        // Fungsi untuk refresh data secara otomatis setiap 30 detik
         setInterval(() => {
            window.location.reload();
         }, 30000); // 30000 ms = 30 detik

    </script>     
</body>
</html>