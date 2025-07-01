<style>
            body {
                background-color: #f0f8ff; 
            }

        </style>
        <div class="status-box">
            <h3>Jadwal Pemakaian Ruangan</h3>
            
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
                    
                @endforeach
                <!-- Legend for color meanings -->
                <div class="legend text-left">
                    <h7>Keterangan Warna:</h7>
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