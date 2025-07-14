<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\UserBanNotification;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $usersQuery = User::query();
        if ($search) {
            $usersQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('user_type', 'like', "%$search%")
                      ->orWhere('contact_number', 'like', "%$search%")
                      ->orWhere('register_type', 'like', "%$search%")
                      ;
            });
        }
        $users = $usersQuery->paginate(10)->appends(['search' => $search]);
        $unreadNotificationCount = Notification::where('is_read', false)->count();
        return view('admin.users.index', compact('users', 'unreadNotificationCount'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'user_type' => 'required|in:admin,user,futsal_manager',
            'password' => 'required|min:8',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'contact_number' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        } else {
            $profilePicturePath = null;
        }

        // Hash the password before saving it
        $password = bcrypt($request->input('password'));

        // Create the user with the new data
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_type' => $request->input('user_type'),
            'contact_number' => $request->input('contact_number'),
            'password' => $password,
            'register_type' => 'Admin Created',
            'profile_picture' => $profilePicturePath,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }


    public function show(User $user)
        {
            // Opsional: Tandai notifikasi terkait sebagai sudah dibaca saat detail dilihat
            Notification::where('notifiable_type', User::class)
                        ->where('notifiable_id', $user->id)
                        ->update(['is_read' => true]);

            return view('admin.users.show', compact('user'));
        }

    public function edit(User $user)
    {
        if ($user->id === 1 && auth()->user()->id !== 1) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot edit the administrator.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'user_type' => 'required|in:admin,futsal_manager,user',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user data
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_type' => $request->input('user_type'),
        ]);

        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->update(['profile_picture' => $profilePicturePath]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function ban(User $user)
    {
        $user->ban();

        if ($user->id === 1 && auth()->user()->id !== 1) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot ban the administrator.');
        }

        // Mail::to($user->email)->send(new UserBanNotification($user, 'banned'));

        return redirect()->route('admin.users.index')->with('success', 'User banned successfully.');
    }

    public function unban(User $user)
    {
        $user->unban();

        // Mail::to($user->email)->send(new UserBanNotification($user, 'unbanned'));

        return redirect()->route('admin.users.index')->with('success', 'User unbanned successfully.');
    }
}
