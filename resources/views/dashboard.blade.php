<!-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Room Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
            max-width: 800px;
        }
        .status {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .ongoing {
            color: #28a745; /* green for ongoing meetings */
        }
        .upcoming {
            color: #007bff; /* blue for upcoming meetings */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Meeting Room Status</h1>
        
        <!-- Current Status -->
        <div class="status text-center" id="roomStatus">Checking status...</div>
        
        <!-- Ongoing Booking -->
        <div id="ongoingBooking" class="mt-4">
            <h3>Current Meeting</h3>
            <div class="alert alert-success" style="display: none;">
                <p><strong>Title:</strong> <span id="ongoingTitle"></span></p>
                <p><strong>Time:</strong> <span id="ongoingTime"></span></p>
            </div>
            <p class="text-muted">No meeting in progress.</p>
        </div>
        
        <!-- Upcoming Bookings -->
        <div id="upcomingBookings" class="mt-4">
            <h3>Upcoming Meetings</h3>
            <ul class="list-group" id="upcomingList"></ul>
        </div>
    </div>

    <!-- JavaScript for fetching and updating data -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function fetchRoomStatus() {
            $.getJSON('/api/room-status/1', function(data) { // Adjust room ID as necessary
                // Update ongoing booking
                if (data.ongoingBooking) {
                    $('#roomStatus').text('Occupied').addClass('ongoing').removeClass('upcoming');
                    $('#ongoingBooking .alert').show();
                    $('#ongoingBooking .text-muted').hide();
                    $('#ongoingTitle').text(data.ongoingBooking.title);
                    $('#ongoingTime').text(data.ongoingBooking.booking_time + ' - ' + data.ongoingBooking.booking_end);
                } else {
                    $('#roomStatus').text('Available').addClass('upcoming').removeClass('ongoing');
                    $('#ongoingBooking .alert').hide();
                    $('#ongoingBooking .text-muted').show();
                }

                // Update upcoming bookings
                $('#upcomingList').empty();
                if (data.upcomingBookings.length > 0) {
                    data.upcomingBookings.forEach(function(booking) {
                        $('#upcomingList').append(
                            '<li class="list-group-item">' +
                            '<strong>' + booking.title + '</strong><br>' +
                            '<span>' + booking.booking_time + ' - ' + booking.booking_end + '</span>' +
                            '</li>'
                        );
                    });
                } else {
                    $('#upcomingList').append('<li class="list-group-item text-muted">No upcoming meetings.</li>');
                }
            });
        }

        // Fetch room status every 60 seconds
        fetchRoomStatus();
        setInterval(fetchRoomStatus, 60000); // 1-minute interval
    </script>
</body>
</html>
