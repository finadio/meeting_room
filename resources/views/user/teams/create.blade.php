@extends('user.layouts.app')
@section('title', 'Create Team')
@section('content')
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h2>Create Team</h2>
        </div>

        <div class="card border-0 shadow">
            <div class="card-body">
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
                <form action="{{ route('user.teams.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Team Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Team</button>
                </form>
                    <div class="mt-3">
                        <p>Already have a team? <a href="{{ route('user.teams.index') }}" style="text-decoration: none; color: red;">Join a Team</a></p>
                    </div>
            </div>
        </div>
    </div>
@endsection
