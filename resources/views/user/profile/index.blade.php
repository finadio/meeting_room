@extends('user.layouts.app')
@section('title', 'User Profile')
@section('content')
    <div class="container py-4">
        <h4 class="font-weight-bold mb-4">Profil Saya</h4>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="mb-0">Data Profil</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            @if ($user->profile_picture && Storage::exists('public/profile_pictures/' . $user->profile_picture))
                                <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture" class="rounded-circle me-3" style="width: 72px; height: 72px; object-fit: cover; border: 2px solid #e2e8f0;">
                            @else
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Default Profile Picture" class="rounded-circle me-3" style="width: 72px; height: 72px; object-fit: cover; border: 2px solid #e2e8f0;">
                            @endif
                            <div class="d-flex align-items-center" style="gap:6px;">
                                <form action="{{ route('profile.update.details') }}" method="post" enctype="multipart/form-data" class="d-inline">
                                    @csrf
                                    <label class="btn btn-outline-primary btn-xs p-1 mb-0">
                                        Upload Foto
                                        <input type="file" class="d-none" name="profile_picture" onchange="this.form.submit()">
                                    </label>
                                </form>
                                @if ($user->profile_picture && Storage::exists('public/profile_pictures/' . $user->profile_picture))
                                    <form action="{{ route('profile.delete.picture') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs p-1">Hapus Foto Profil</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('profile.update.details') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="dob" value="{{ $user->dob }}" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="tel" name="contact_number" value="{{ $user->contact_number }}" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control">{{ $user->address }}</textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="mb-0">Ganti Password</h5>
                    </div>
                    <div class="card-body">
                        @if(session('password_success'))
                            <div class="alert alert-success">{{ session('password_success') }}</div>
                        @endif
                        @if(session('password_error'))
                            <div class="alert alert-danger">{{ session('password_error') }}</div>
                        @endif
                        <form action="{{ route('profile.update.password') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Password Lama</label>
                                <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required autocomplete="new-password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required autocomplete="new-password">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-key"></i> Ganti Password</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bx bx-error"></i> Danger Zone</h5>
                    </div>
                    <div class="card-body">
                        <form id="deleteAccountForm" action="{{ route('delete.account') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Akun</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    {{-- REMOVED DUPLICATE SCRIPTS: jQuery, Bootstrap JS, and admin_dashboard.js --}}
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css" />
@endsection