@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">⚙️ Edit Profile</h4>
            </div>
            
            <div class="card-body">
                <form action="/profile/update" method="POST">
                    @csrf <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ $user->name }}&size=150&background=random" class="rounded-circle mb-2" alt="Profile">
                            <p class="text-muted small">Profile photo auto-generated hai</p>
                        </div>

                        <div class="col-md-8">
                            
                            <div class="mb-3">
                                <label class="form-label">Pura Naam (Name)</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-success fw-bold">💰 Monthly Budget (Target)</label>
                                <input type="number" name="budget" class="form-control" value="{{ $user->budget }}" placeholder="Ex: 10000">
                                <small class="text-muted">Apna mahine ka limit set karein.</small>
                            </div>

                            <hr> <h6 class="text-danger">🔒 Change Password (Optional)</h6>
                            <p class="small text-muted">Agar password nahi badalna hai toh inhein khali chhod dein.</p>

                            <div class="mb-3">
                                <label class="form-label">Naya Password</label>
                                <input type="password" name="password" class="form-control" placeholder="New Password" autocomplete="new-password">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password">
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="/" class="text-decoration-none">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection