<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Arsip PTPN4</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📦</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #14532d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        body::before {
            content: '';
            position: fixed;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(22,163,74,0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -200px;
            left: -200px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.1);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #0f172a 0%, #166534 100%);
            padding: 2.5rem 2rem 2rem;
            text-align: center;
        }

        .login-logo {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.25);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            backdrop-filter: blur(10px);
        }

        .login-body { padding: 2rem; }

        .input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 18px;
            height: 18px;
            pointer-events: none;
        }

        .input-field {
            width: 100%;
            padding: 0.75rem 0.875rem 0.75rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .input-field:focus {
            border-color: #16a34a;
            background: white;
            box-shadow: 0 0 0 3px rgba(22,163,74,0.1);
        }

        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #166534, #16a34a);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.025em;
            box-shadow: 0 4px 15px rgba(22,163,74,0.35);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(22,163,74,0.45);
        }

        .btn-login:active { transform: translateY(0); }
    </style>
</head>
<body>
<div class="login-wrapper">

    {{-- Branding atas --}}
    <div style="text-align: center; margin-bottom: 1.25rem;">
        <div style="color: rgba(255,255,255,0.6); font-size: 0.8rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.25rem;">PT Perkebunan Nusantara IV</div>
        <div style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">Sistem Repository Arsip Digital</div>
    </div>

    <div class="login-card">

        {{-- Header --}}
        <div class="login-header">
            <div class="login-logo">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 36px; height: 36px; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                    <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16"/>
                    <line x1="10" y1="14" x2="14" y2="14"/>
                </svg>
            </div>
            <h1 style="color: white; font-size: 1.5rem; font-weight: 800; margin: 0 0 0.25rem;">Repository Arsip</h1>
            <p style="color: rgba(255,255,255,0.6); font-size: 0.85rem; margin: 0;">Masuk untuk melanjutkan</p>
        </div>

        {{-- Body --}}
        <div class="login-body">

            {{-- Error flash --}}
            @if(session('error'))
            <div class="alert alert-error" style="margin-bottom: 1.25rem;">
                <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 1.25rem;">
                <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" autocomplete="off">
                @csrf

                {{-- Username --}}
                <div class="input-group">
                    <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em;">Username</label>
                    <div style="position: relative;">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" name="username" class="input-field {{ $errors->has('username') ? 'border-red-400' : '' }}"
                            placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                    </div>
                    @error('username')
                    <div class="form-error" style="margin-top: 0.35rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="input-group">
                    <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em;">Password</label>
                    <div style="position: relative;">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <input type="password" name="password" id="passwordField" class="input-field"
                            placeholder="Masukkan password" required>
                        <button type="button" onclick="togglePassword()" style="position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 0;">
                            <svg id="eyeIcon" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')
                    <div class="form-error" style="margin-top: 0.35rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                    <input type="checkbox" name="remember" id="remember" style="width: 16px; height: 16px; accent-color: #16a34a;">
                    <label for="remember" style="font-size: 0.85rem; color: #64748b; cursor: pointer;">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
    </div>

    {{-- Footer --}}
    <div style="text-align: center; margin-top: 1.5rem; color: rgba(255,255,255,0.4); font-size: 0.75rem;">
        &copy; {{ date('Y') }} PT Perkebunan Nusantara IV. All rights reserved.
    </div>
</div>

<script>
    function togglePassword() {
        const f = document.getElementById('passwordField');
        f.type = f.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>
