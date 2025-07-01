@extends('user.layouts.app')
@section('title', 'Booking Confirmation')
@section('content')

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h2>Booking Confirmation</h2>
        </div>

        <div class="card border-0 shadow">
            <div class="card-body">
                <p class="card-text">Thank you for choosing us! Please review the details of your booking:</p>

                <ul class="list-group">
                    <div class="card border-0 shadow">
                        @if($facility->image_path)
                            <img src="{{ asset('storage/facility_images/' . basename($facility->image_path)) }}" class="card-img-top rounded-4" alt="{{ $facility->name }}">
                        @endif
                    </div>
                    <li class="list-group-item"><strong>Facility Name:</strong> {{ $facility->name }}</li>
                    <li class="list-group-item"><strong>Booking Date:</strong> {{ session('booking.date') }}</li>
                    <li class="list-group-item"><strong>Booking Time:</strong> {{ date('h:i A', strtotime(session('booking.time'))) }}</li>
                    <li class="list-group-item"><strong>Booking Hours:</strong> {{ (session('booking.hours')) }} hour</li>
                    <!-- <li class="list-group-item"><strong>Total Price:</strong> Rs. {{ (session('booking.amount')) }}</li> -->
                </ul>

                <!-- Display the map -->
                <!-- <div id="map" style="height: 300px; border-radius: 8px; overflow: hidden;"></div> -->

                <div class="mt-4">
                     <p class="card-text">Choose your payment method:</p>

                    Payment methods

                    <form action="{{ route('user.bookings.stripe.payment') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type='hidden' name="paymentMethod" value="Stripe">
                        <input type='hidden' name="total" value="{{ (session('booking.amount')) }}">
                        <input type='hidden' name="productname" value="{{ $facility->name }}">
                        <a href="#" onclick="document.forms[0].submit(); return false;">
                            <img src="{{ asset('img/stripe.png') }}" alt="Pay with Stripe" style="width: 150px;">
                        </a>
                    </form>
                    <form id="paymentForm" action="{{ route('payment.success') }}" method="GET">
                        @csrf
                        <div class="payment-options">
                            <div class="payment-option">
                                <input type="radio" class="form-check-input" id="codPayment" name="paymentMethod" value="Cash on Delivery">
                                <label class="radio-label" for="codPayment">
                                    <img src="{{ asset('img/cod.png') }}" alt="cod">
                                    <span>Cash on Delivery</span>
                                </label>
                            </div>
                        </div> 

                        <div class="mt-4">
                            <button type="submit" class="book-btn" onclick="proceedToPayment()"><i class='bx bx-credit-card'></i> Proceed to Booking</button>
                        </div>
                    </form>
                </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // document.addEventListener('DOMContentLoaded', function () {
        //     // Initialize map with facility coordinates
        //     var coordinates = [{{ $facility->map_coordinates }}];
        //     var map = L.map('map').setView(coordinates, 15);

        //     // Add OpenStreetMap tile layer
        //     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //         attribution: '&copy; OpenStreetMap contributors'
        //     }).addTo(map);

        //     // Add marker for the facility location
        //     var marker = L.marker(coordinates).addTo(map);
        // });

        function proceedToPayment() {
            var paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

            if (paymentMethod) {
                if (paymentMethod.id === 'codPayment') {
                    // If Cash on Delivery is selected, download the receipt
                    var receiptUrl = "{{ route('generate.receipt') }}";

                    // Trigger the receipt download
                    var receiptDownloadLink = document.createElement('a');
                    receiptDownloadLink.href = receiptUrl;
                    receiptDownloadLink.download = 'receipt.txt';
                    document.body.appendChild(receiptDownloadLink);
                    receiptDownloadLink.click();
                    document.body.removeChild(receiptDownloadLink);

                    // Redirect to payment success page
                    window.location.href = "{{ route('payment.success') }}";
                } else {
                    console.log('Proceeding to payment for other methods');
                }
            } else {
                alert('Please select a payment method.');
            }
        }

    </script>

@endsection

@section('styles')
    <style>
        .radio-label img {
            max-width: 100px;
            margin-bottom: 5px;
        }

        .payment-option input[type="radio"] {
            position: absolute;
            opacity: 3;
            pointer-events: none;
        }

        .book-btn {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        .book-btn:hover {
            background-color: #1593e7;
            color: #FF5733;
            text-decoration: none;
            transform: scale(1.05);
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
@endsection
