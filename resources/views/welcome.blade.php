<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBS HRMS - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-100 to-white min-h-screen">
    <div class="container mx-auto px-4 h-screen flex flex-col items-center justify-center">        <div class="text-center bg-white rounded-xl shadow-2xl p-10 max-w-2xl w-full backdrop-blur-sm bg-opacity-90">
            <div class="mb-8">
                <img src="{{ asset('images/CBS IOT PNG.png') }}" alt="CBS Logo" class="h-32 mx-auto">
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to CBS HRMS</h1>
            <p class="text-xl text-gray-600 mb-8">Developed by <span class="font-semibold text-blue-600">Sangram Roygupta</span></p>
            <div class="space-y-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        Login
                    </a>
                @endauth
            </div>
            <div class="mt-8 text-gray-500">
                <p class="text-sm">© {{ date('Y') }} CBS HRMS. All rights reserved.</p>
            </div>
        </div>
    </div>

    <div class="fixed bottom-4 right-4 text-sm text-gray-500">
        Version 1.0.0
    </div>
</body>
</html> 