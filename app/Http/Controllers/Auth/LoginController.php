<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        try {
            // Validate the form data
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Attempt to log in the user
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                // Check if the user is banned
                if (Auth::user()->isBanned()) {
                    Auth::logout();
                    return back()->withErrors(['email' => 'Your account is banned. Please contact the admin for assistance.'])->withInput();
                }
                
                $authUser = Auth::user(); // Dapatkan pengguna yang terautentikasi

            // Cek tipe pengguna dan arahkan sesuai
                if ($authUser->user_type == 'admin' || $authUser->user_type == 'futsal_manager') {
                // Perbarui last_check_status untuk admin atau manager futsal
                $authUser->last_check_status = now();
                $authUser->save(); // Simpan perubahan

                // Panggil fungsi checkBookingStatus untuk memperbarui status booking
                app('App\Http\Controllers\Admin\BookingsController')->checkBookingStatus();

                \Log::info("Last check status diupdate untuk admin: " . $authUser->id);

                    return redirect()->route('admin.dashboard');
                } elseif (Auth::user()->user_type == 'user') {
                    return redirect()->route('user.dashboard');
                }
            }
            
            // \Log::info("Updated last_check_status for admin: " . $user->id);
            // \Log::info("Last check status diupdate untuk pengguna: " . $user->id);
            // Authentication failed
            return back()->withErrors(['email' => 'Invalid email or password'])->withInput();
        } catch (\Exception $e) {
            // Handle other types of exceptions
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        // Check if the user already exists in your database
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            // Check if the existing user is banned
            if ($existingUser->isBanned()) {
                return redirect()->route('login')->withErrors(['email' => 'Your account is banned. Please contact the admin for assistance.'])->withInput();
            }

            Auth::login($existingUser);
        } else {
            // Create a new user with Google credentials
            $newUser = new User();
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->password = bcrypt(Str::random(8));
            $newUser->verified = true;
            $newUser->register_type = 'Google';
            $newUser->save();

            // Send welcome email to the new user
            Mail::to($newUser)->send(new WelcomeMail($newUser));

            // Check if the new user is banned
            if ($newUser->isBanned()) {
                return redirect()->route('login')->withErrors(['email' => 'Your account is banned. Please contact the admin for assistance.'])->withInput();
            }

            Auth::login($newUser);
        }

        // Retrieve user details again after logging in
        $loggedInUser = Auth::user();

        // Check user type and redirect accordingly
        if ($loggedInUser->user_type == 'admin' || $loggedInUser->user_type == 'futsal_manager') {
            return redirect()->route('admin.dashboard');
        } elseif ($loggedInUser->user_type == 'user') {
            return redirect()->route('user.dashboard');
        }

        // Default return statement in case none of the conditions are met
        return redirect()->route('user.dashboard');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login')->with('success', 'Logout successful.');
    }

    public function authenticated(Request $request, $user)
    {
        // Pastikan user adalah admin
        if ($user->isAdmin()) {
            // Cek apakah hari ini sudah dijalankan
            if (!$user->last_check_status || $user->last_check_status < now()->toDateString()) {
                // Panggil fungsi checkBookingStatus
                $this->checkBookingStatus();

                // Update tanggal terakhir menjalankan fungsi
                $user->last_check_status = now()->toDateString();
                $user->save();
            }
        }

        return redirect()->intended($this->redirectPath());
    }

    protected function checkBookingStatus()
    {
        // Logika untuk mengecek dan mengupdate status booking
        Booking::where('booking_date', '<', now())
            ->where('status', '!=', 'Disetujui')
            ->update(['status' => 'selesai']);
    }

}
