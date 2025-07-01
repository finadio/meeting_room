@extends('user.layouts.app')
@section('title', 'User Profile')
@section('content')
    <br>
    <br>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Profile Page
        </h4>
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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                           href="#account-general">Account Details</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                           href="#account-additional">Additional Details</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                           href="#account-change-password">Security</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                           href="#account-danger-zone">Danger Zone</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <form action="{{ route('admin.profile.update.details') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body media align-items-center">
                                    @if ($user->profile_picture && Storage::exists('public/profile_pictures/' . $user->profile_picture))
                                        <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}" alt class="d-block ui-w-80">
                                    @else
                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt class="d-block ui-w-80">
                                    @endif

                                    <div class="media-body ml-4">
                                        <label class="btn btn-outline-primary">
                                            Upload new photo
                                            <input type="file" class="account-settings-fileinput" name="profile_picture">
                                        </label> &nbsp;
                                        <div class="text-light small mt-1">Allowed JPG, GIF, or PNG. Max size of 800K</div>
                                    </div>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control mb-1" name="name" value="{{ $user->name }}">
                                        @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">E-mail</label>
                                        <input type="text" class="form-control mb-1" name="email" value="{{ $user->email }}">
                                        @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary">Save changes</button>&nbsp;
                                    <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="account-additional">
                            <form action="{{ route('profile.update.additionaldetails') }}" method="post">
                                @csrf
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label for="dob">Date of Birth:</label>
                                        <input type="date" id="dob" name="dob" value="{{ $user->dob }}" class="form-control">
                                        @error('dob')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="gender">Gender:</label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ $user->gender === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="contact_number">Contact Number:</label>
                                        <input type="tel" id="contact_number" name="contact_number" value="{{ $user->contact_number }}" class="form-control">
                                        @error('contact_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address:</label>
                                        <textarea id="address" name="address" class="form-control">{{ $user->address }}</textarea>
                                        @error('address')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="preferred_position">Preferred Futsal Position:</label>
                                        <input type="text" id="preferred_position" name="preferred_position" value="{{ $user->preferred_position }}" class="form-control">
                                        @error('preferred_position')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="experience_level">Futsal Experience Level:</label>
                                        <select id="experience_level" name="experience_level" class="form-control">
                                            <option value="beginner" {{ $user->experience_level === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                            <option value="intermediate" {{ $user->experience_level === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                            <option value="advanced" {{ $user->experience_level === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                        </select>
                                        @error('experience_level')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary">Save changes</button>&nbsp;
                                    <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="account-change-password">
                            <form action="{{ route('admin.profile.update.password') }}" method="post">
                                @csrf
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label for="password" class="form-label">New password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="password">
                                            <div class="input-group-append">
                                                <span class="input-group-text" onclick="password()" id="show-hide-password" style="cursor: pointer;"><i class="mdi mdi-eye"></i></span>
                                            </div>
                                        </div>
                                        @error('password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password-confirm" class="form-label">Confirm new password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password-confirm" id="password-confirm">
                                            <div class="input-group-append">
                                                <span class="input-group-text" onclick="passwordconfirm()" id="show-hide-password-confirm" style="cursor: pointer;"><i class="mdi mdi-eye"></i></span>
                                            </div>
                                        </div>
                                        @error('password-confirm')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="submit" class="btn btn-primary">Save changes</button>&nbsp;
                                        <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="account-danger-zone">
                            <h4>Danger Zone</h4>
                            <p>Be careful! Deleting your account is irreversible.</p>
                            <form id="deleteAccountForm" action="{{ route('delete.account') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Account</button>
                            </form>
                        </div>

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
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="{{ asset('js/admin_dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css" />
@endsection
