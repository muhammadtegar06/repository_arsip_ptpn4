@extends('layouts.app')
@section('title', 'Ubah Password')
@section('page-title', 'Keamanan Akun')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <span>Ubah Password</span>
@endsection

@section('content')

<div style="max-width: 450px; margin: 0 auto;">
    <div class="card">
        <div class="card-header" style="background: #f8fafc;">
            <h3 style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Ubah Password Anda</h3>
        </div>
        <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 0.875rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                    <svg style="width:20px;height:20px;color:#2563eb;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div style="font-size: 0.8rem; color: #1e3a8a;">
                        Pastikan password baru Anda kuat dan tidak mudah ditebak (minimal 6 karakter). Jangan bagikan password Anda ke siapapun.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password Saat Ini <span style="color: #dc2626;">*</span></label>
                    <input type="password" name="password_lama" class="form-input {{ $errors->has('password_lama') ? 'error' : '' }}" required>
                    @error('password_lama')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru <span style="color: #dc2626;">*</span></label>
                    <input type="password" name="password_baru" class="form-input {{ $errors->has('password_baru') ? 'error' : '' }}" required>
                    @error('password_baru')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru <span style="color: #dc2626;">*</span></label>
                    <input type="password" name="password_baru_confirmation" class="form-input" required>
                </div>

            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-outline">Reset Form</button>
                <button type="submit" class="btn btn-primary">
                    <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
