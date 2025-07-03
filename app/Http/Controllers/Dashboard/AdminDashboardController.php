<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Notification;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Pastikan ini ada

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userCount = User::count();
        $newUsersCount = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        $bookingCount = Booking::count();
        $facilityCount = Facility::count();
        $tournamentCount = Tournament::count(); 


        $unreadNotificationCount = Notification::where('is_read', false)->count();
        
        $userTypes = User::select('user_type', DB::raw('count(*) as count'))
            ->groupBy('user_type')
            ->pluck('count', 'user_type');
        
        $userCounts = [
            'admin' => $userTypes->get('admin', 0),
            'user' => $userTypes->get('user', 0),
        ];
        
        $bookings = Booking::all();
        $bookedDates = $bookings->map(function ($booking) {
            $formattedDate = Carbon::parse($booking->booking_date)->format('Y-m-d');

            return [
                'title' => $booking->meeting_title ?? $booking->facility->name,
                'start' => Carbon::parse($formattedDate . ' ' . $booking->booking_time)->toIso8601String(),
                'end' => Carbon::parse($formattedDate . ' ' . $booking->booking_end)->toIso8601String(),
                'status' => $booking->status,
                'classNames' => [
                    'fc-event-' . strtolower(str_replace(' ', '-', $booking->status)), // Membuat class dari status, misal 'fc-event-disetujui'
                ],
            ];
        });

        return view('admin.dashboard', compact(
            'user', 
            'userCount', 
            'bookingCount', 
            'facilityCount', 
            'tournamentCount', 
            'unreadNotificationCount', 
            'bookedDates', 
            'userCounts',
            'newUsersCount', 
        ));
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
            ->where('booking_date', $currentDate) // Ubah 'date' menjadi 'booking_date'
            ->where('booking_time', '<=', $currentTime)
            ->where('booking_end', '>', $currentTime)
            ->first();

        // Ambil booking mendatang setelah waktu saat ini
        $upcomingBookings = Booking::where('facility_id', $id)
            ->where('booking_date', $currentDate) // Ubah 'date' menjadi 'booking_date'
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