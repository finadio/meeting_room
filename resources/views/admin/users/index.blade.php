@extends('admin.layouts.admin_dashboard')
@section('title', 'User Management')

@section('content')
    <div class="container">
        <h4 class="font-weight-bold py-3 mb-4">User Management</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('message'))
            <div class="alert alert-danger">{{ session('message') }}</div>
        @endif

        <div class="table-responsive">
            <div class="text-right mb-3">
                <a href="{{ route('admin.users.create') }}" class="btn" style="background-color: #3C91E6; border-color: #3C91E6; color: white">
                    <i class='bx bx-user-plus'></i> Add User
                </a>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>S.N</th>
                    <th style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">User ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">User Type</th>
                    <th>Status</th>
                    <th style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Verification Status</th>
                    <th style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Last Active</th>
                    <th style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Registration At</th>
                    <th style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Register Type</th>
                    <th style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->id }}</td>
                        <td>
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}" alt="User Image" class="d-block ui-w-80 rounded-circle border" style="max-width: 90px; max-height: 90px;">
                            @else
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="User Image" class="d-block ui-w-80 rounded-circle border" style="max-width: 40px; max-height: 40px;">
                            @endif
                        </td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->user_type === 'user')
                                <span class="badge badge-secondary">{{ ucfirst($user->user_type) }}</span>
                            @elseif($user->user_type === 'futsal_manager')
                                <span class="badge badge-info">{{ ucfirst($user->user_type) }}</span>
                            @elseif($user->user_type === 'admin')
                                <span class="badge badge-danger">{{ ucfirst($user->user_type) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($user->isBanned())
                                <span class="badge badge-danger">Banned</span>
                            @else
                                <span class="badge badge-success">Active</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($user->verified)
                                <i class='bx bx-check-circle text-success'></i>
                            @else
                                <i class='bx bx-x-circle text-danger'></i>
                            @endif
                        </td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            @if($user->last_active)
                                {{ \Carbon\Carbon::parse($user->last_active)->diffForHumans(null, true) }} ago
                            @else
                                N/A
                            @endif
                        </td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ \Carbon\Carbon::parse($user->created_at)->format('F j, Y / h:i A') }}</td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $user->register_type ?? 'Administrator' }}
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm" title="View">
                                <i class='bx bx-show'></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class='bx bx-edit'></i>
                            </a>
                            @if($user->isBanned())
                                <form action="{{ route('admin.users.unban', $user) }}" method="post" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Unban" onclick="return confirm('Are you sure you want to unban this user?')">
                                        <i class='bx bx-check'></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.ban', $user) }}" method="post" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" title="Ban" onclick="return confirm('Are you sure you want to ban this user?')">
                                        <i class='bx bx-block'></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
                {{ $users->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_user_management.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
@endsection
