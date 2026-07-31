<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Shop Data --}}
    @php
        $shopSetting = \Illuminate\Support\Facades\DB::table('shop_settings')->first();
        $shopName = $shopSetting->shop_name ?? 'NS Enterprises';
        $shopLogo = $shopSetting->logo ?? null;
    @endphp

    <title>Login - {{ $shopName }}</title>
    
    @if(file_exists(public_path('favicon.ico')))
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.svg') }}" type="image/x-icon">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1920') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 32, 39, 0.7);
            z-index: 0;
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 950px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
            min-height: 600px;
            animation: fadeInDown 0.8s;
        }

        .login-branding {
            background: linear-gradient(135deg, rgba(15, 32, 39, 0.9) 0%, rgba(32, 58, 67, 0.9) 50%, rgba(44, 83, 100, 0.9) 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
            width: 50%;
            transition: all 0.5s;
        }

        .login-form-container {
            padding: 60px 50px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff;
        }

        .form-control-xl {
            padding: 14px 15px;
            font-size: 1rem;
            border-radius: 12px;
            border: 1px solid #dce7f1;
            transition: all 0.3s;
        }

        .form-control-xl:focus {
            border-color: #435ebe;
            box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.15);
            transform: translateY(-2px);
        }

        .auth-title {
            font-size: 2rem;
            color: #2c3e50;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .btn-login {
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: 0.4s;
            color: white;
        }
        
        .btn-login:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: #fff;
        }

        .toggle-password {
            position: absolute;
            right: 20px;
            top: 15px;
            cursor: pointer;
            color: #adb5bd;
            transition: 0.3s;
        }

        .toggle-password:hover { color: #435ebe; }

        .form-group i.bi-person, .form-group i.bi-lock {
            font-size: 1.2rem;
        }

        .logo-box {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @media (max-width: 768px) {
            .login-branding, .login-form-container {
                width: 100%;
                min-height: auto;
                padding: 40px 30px;
            }
            .login-branding { display: none; }
        }
    </style>
</head>

<body>

    <div class="login-card animate__animated animate__zoomIn">
        <div class="login-branding">
            <div class="logo-box mb-4">
                @if(file_exists(public_path('favicon.ico')))
                    <img src="{{ asset('favicon.ico') }}?v={{ time() }}" alt="Logo" style="height: 100px; width: auto; background: rgba(255,255,255,0.15); padding: 15px; border-radius: 20px; backdrop-filter: blur(5px);">
                @elseif($shopLogo)
                    <img src="{{ asset('storage/' . $shopLogo) }}" alt="Logo" style="height: 100px; width: auto; background: rgba(255,255,255,0.15); padding: 15px; border-radius: 20px; backdrop-filter: blur(5px);">
                @else
                    <i class="bi bi-cpu fs-1" style="font-size: 4rem !important;"></i>
                @endif
            </div>
            <h1 class="fw-bold mb-2 animate__animated animate__fadeInUp animate__delay-1s">{{ $shopName }}</h1>
            <p class="text-white-50 fs-5 animate__animated animate__fadeInUp animate__delay-1s">Advanced POS Intelligence</p>
            <div class="w-25 border-bottom border-white opacity-25 my-4"></div>
            <p class="small text-white-50 animate__animated animate__fadeIn animate__delay-2s">Management System v2.0</p>
        </div>

        <div class="login-form-container">
            <div class="mb-5 animate__animated animate__fadeInRight">
                <h3 class="auth-title">Welcome Back</h3>
                <p class="text-muted">Please enter your details to continue.</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm" class="animate__animated animate__fadeInUp animate__delay-1s">
                @csrf
                
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="email" name="email" id="email" 
                        class="form-control form-control-xl @error('email') is-invalid @enderror" 
                        placeholder="Email Address" value="{{ old('email') }}" required autofocus
                        oninput="this.value = this.value.trim().toLowerCase()"> 
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>

                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" name="password" id="password"
                        class="form-control form-control-xl" 
                        placeholder="Password" required>
                    <div class="form-control-icon">
                        <i class="bi bi-lock"></i>
                    </div>
                    <i class="bi bi-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                        <label class="form-check-label text-muted small" for="flexCheckDefault">
                            Remember this device
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100 shadow-lg mt-2" id="submitBtn">Sign In</button>
            </form>

            <div class="text-center mt-5 animate__animated animate__fadeIn animate__delay-2s">
                <p class="small text-muted mb-0">Engineered by <strong>NS Enterprises</strong> &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>

    <script>
        /**
         * 419 Page Expired ගැටලුව සඳහා ස්ථිර විසඳුම:
         * පිටුව පෙන්වන සෑම විටම CSRF Token එක Refresh කරන ලෙස බල කරයි.
         */
        window.addEventListener('pageshow', function (event) {
            // Mobile browser එකේ 'Back' button එක එබූ විට හෝ cache එකෙන් load වූ විට
            if (event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });

        // Form එක Submit වන විට Token එක mismatch වීම වැළැක්වීමට
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Signing In...';
            btn.disabled = true;
        });

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
        
        document.querySelectorAll('.form-control-xl').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.querySelector('i').style.color = '#435ebe';
            });
            input.addEventListener('blur', () => {
                input.parentElement.querySelector('i').style.color = '#adb5bd';
            });
        });
    </script>

</body>
</html>
