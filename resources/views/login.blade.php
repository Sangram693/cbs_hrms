<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Login</title>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border border-gray-200 animate-fade-in flex flex-col justify-center">
    <div class="flex justify-center mb-4">
        <img src="{{ asset('images/CBS IOT PNG.png') }}" alt="cbsiot.live Logo" class="max-h-16 max-w-xs w-auto h-16 object-contain">
    </div>
    <h2 class="text-2xl font-bold mb-2 text-center text-blue-900">HRMS Login</h2>
    <p class="text-center text-gray-500 mb-6 tracking-wide">by <span class="font-semibold text-green-700">cbsiot.live</span></p>
    @if ($errors->any())
        <div class="mb-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center animate-pulse" role="alert">
                <span class="block sm:inline font-semibold">{{ $errors->first() }}</span>
            </div>
        </div>
    @endif
    <form id="login-form" method="POST" action="{{ route('login.post') }}" class="space-y-4">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 mb-2 font-medium" for="email">Email</label>
            <input class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 transition" type="email" name="email" id="email" required autofocus value="{{ old('email') }}">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 mb-2 font-medium" for="password">Password</label>
            <input class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 transition" type="password" name="password" id="password" required autocomplete="current-password">
        </div>
        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white font-bold py-2 rounded shadow hover:from-blue-700 hover:to-blue-600 transition">Login</button>
    </form>
    <div class="mt-8 text-center text-xs text-gray-400">&copy; {{ date('Y') }} cbsiot.live. All rights reserved.</div>
</div>
@stack('scripts')
</body>
</html>
