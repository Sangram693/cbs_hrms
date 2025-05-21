<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #e0e7ff 0%, #f0fdf4 100%); }
        .login-logo { filter: drop-shadow(0 2px 8px rgba(59,130,246,0.15)); }
        .login-card { box-shadow: 0 8px 32px 0 rgba(31, 41, 55, 0.12); }
        .login-title { letter-spacing: 0.02em; }
        .login-btn { transition: all 0.2s; }
        .login-btn:active { transform: scale(0.97); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
<div class="w-full max-w-md p-8 bg-white rounded-2xl login-card border border-gray-100 animate-fade-in flex flex-col justify-center">
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/CBS IOT PNG.png') }}" alt="cbsiot.live Logo" class="login-logo max-h-20 w-auto h-20 object-contain">
    </div>
    <h2 class="text-3xl font-extrabold mb-2 text-center text-blue-800 login-title">HRMS Login</h2>
    <p class="text-center text-gray-500 mb-6 tracking-wide">by <span class="font-semibold text-green-700">cbsiot.live</span></p>
    @if ($errors->any())
        <div class="mb-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center animate-pulse" role="alert">
                <span class="block sm:inline font-semibold">{{ $errors->first() }}</span>
            </div>
        </div>
    @endif
    <form id="login-form" method="POST" action="{{ route('login.post') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-gray-700 mb-2 font-semibold" for="email">Email</label>
            <input class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition shadow-sm" type="email" name="email" id="email" value="admin_cbs@example.com" required autofocus value="{{ old('email') }}">
        </div>
        <div>
            <label class="block text-gray-700 mb-2 font-semibold" for="password" value="password">Password</label>
            <div class="relative">
                <input class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition shadow-sm pr-10" type="password" name="password" id="password" value="password" required autocomplete="current-password">
                <button type="button" id="togglePassword" tabindex="-1" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 focus:outline-none" aria-label="Show password">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="eyePath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path id="eyePath2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
        </div>
        <button type="submit" class="w-full login-btn bg-gradient-to-r from-blue-600 to-blue-500 text-white font-bold py-2.5 rounded-lg shadow hover:from-blue-700 hover:to-blue-600 transition text-lg">Login</button>
    </form>
    <div class="mt-8 text-center text-xs text-gray-400">&copy; {{ date('Y') }} cbsiot.live. All rights reserved.</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        let isVisible = false;
        togglePassword.addEventListener('click', function (e) {
            e.preventDefault();
            isVisible = !isVisible;
            passwordInput.type = isVisible ? 'text' : 'password';
            if (isVisible) {
                eyeIcon.innerHTML = `<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.292m3.087-2.727A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.421 5.568M15 12a3 3 0 11-6 0 3 3 0 016 0z\" />\n<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 3l18 18\" />`;
            } else {
                eyeIcon.innerHTML = `<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 12a3 3 0 11-6 0 3 3 0 016 0z\" />\n<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\" />`;
            }
        });
    });
</script>
</body>
</html>
