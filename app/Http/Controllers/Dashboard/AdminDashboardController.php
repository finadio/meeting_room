<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Notification;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userCount = User::count();
        $bookingCount = Booking::count();
        $facilityCount = Facility::count();
        $tournamentCount = Tournament::count();
        $unreadNotificationCount = Notification::where('is_read', false)->count();
        $userCounts = User::select('user_type', DB::raw('count(*) as count'))
            ->groupBy('user_type')
            ->pluck('count', 'user_type');

        $events = [];
        $user = auth()->user();
        $bookings = Booking::all();

        $bookedDates = $bookings->map(function ($booking) {
            $bookingDate = \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d');
            $bookingTime = \Carbon\Carbon::parse($booking->booking_time)->format('H:i:s');
            // $bookingStatus = \Carbon\Carbon::parse($booking->status);
            $facilityName = $booking->facility->name;

            $title = "{$facilityName} - " . \Carbon\Carbon::parse($booking->booking_time)->format('h:i A');

            return [
                'title' => $title,
                'start' => $bookingDate,
                'bookingDate' => $bookingDate,
                'bookingTime' => $bookingTime,
                'facilityName' => $facilityName,
                'className' => 'badge badge-danger badge-pill',
                'borderColor' => 'red',
            ];
        });

        return view('admin.dashboard', compact('user', 'userCount', 'bookingCount', 'facilityCount', 'tournamentCount', 'unreadNotificationCount', 'bookedDates', 'userCounts'));
    }

    public function notifications()
    {
        $notifications = Notification::with('facility.user')->where('is_read', false)->get();
        $unreadNotificationCount = Notification::where('is_read', false)->count();

        return view('admin.notifications.index', compact('notifications', 'unreadNotificationCount'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return redirect()->route('admin.notifications.index');
    }


    public function viewSubmission($id)
    {
        try {
            $facility = Facility::findOrFail($id);

            return view('user.facility_submissions.view', compact('facility'));
        } catch (\Exception $e) {
            return redirect()->route('user.facility_submissions.create')->with('error', 'Facility not found.');
        }
    }

    public function getRoomStatus($id)
    {
        // Dapatkan tanggal dan waktu saat ini
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        // Ambil booking yang sedang berlangsung
        $ongoingBooking = Booking::where('facility_id', $id)
            ->where('date', $currentDate)
            ->where('booking_time', '<=', $currentTime)
            ->where('booking_end', '>', $currentTime)
            ->first();

        // Ambil booking mendatang setelah waktu saat ini
        $upcomingBookings = Booking::where('facility_id', $id)
            ->where('date', $currentDate)
            ->where('booking_time', '>', $currentTime)
            ->orderBy('booking_time')
            ->get();

        // Struktur data yang dikembalikan
        return response()->json([
            'ongoingBooking' => $ongoingBooking,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}

