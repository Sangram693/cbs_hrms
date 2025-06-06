<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-blue-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside
            class="hidden lg:flex flex-col w-64 bg-white/90 border-r border-blue-100 shadow-lg py-8 px-4 sticky top-0 h-screen">
            <div class="flex flex-col items-center mb-8 px-2">
                <div class="mb-2">
                    <img src="https://cbsiot.live/assate/images/cbsiotlogo.svg" alt="cbsiot.live Logo"
                        class="h-12 w-auto ">
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-blue-900">HRMS</span>
            </div>
            <nav class="flex-1">
                <ul class="flex flex-col gap-2 text-base font-medium">
                    <li><a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fa-solid fa-house text-blue-600 h-5 w-5"></i>Dashboard</a>
                    </li>
                    @if (auth()->check() && auth()->user()->isSuperAdmin())
                        <li><a href="{{ route('companies.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-building text-cyan-600 h-5 w-5"></i>Companies</a>
                        </li>
                        <li><a href="{{ route('employees.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-users text-emerald-600 h-5 w-5"></i>Employees</a>
                        </li>
                        <li><a href="{{ route('departments.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-sitemap text-yellow-600 h-5 w-5"></i>Departments</a>
                        </li>
                        <li><a href="{{ route('designations.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-briefcase text-purple-600 h-5 w-5"></i>Designations</a>
                        </li>
                        <li><a href="{{ route('attendance.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-calendar-check text-pink-600 h-5 w-5"></i>Attendance</a>
                        </li>
                        <li><a href="{{ route('leaves.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-plane-departure text-indigo-600 h-5 w-5"></i>Leaves</a>
                        </li>
                        <li><a href="{{ route('salaries.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-money-bill-wave text-rose-600 h-5 w-5"></i>Salaries</a>
                        </li>
                        <li><a href="{{ route('trainings.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-chalkboard-teacher text-teal-600 h-5 w-5"></i>Trainings</a>
                        </li>
                    @elseif(auth()->check() && auth()->user()->isAdmin())
                        <li><a href="{{ route('employees.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-users text-emerald-600 h-5 w-5"></i>Employees</a>
                        </li>
                        <li><a href="{{ route('departments.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-sitemap text-yellow-600 h-5 w-5"></i>Departments</a>
                        </li>
                        <li><a href="{{ route('designations.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-briefcase text-purple-600 h-5 w-5"></i>Designations</a>
                        </li>
                        <li><a href="{{ route('attendance.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-calendar-check text-pink-600 h-5 w-5"></i>Attendance</a>
                        </li>
                        <li><a href="{{ route('leaves.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-plane-departure text-indigo-600 h-5 w-5"></i>Leaves</a>
                        </li>
                        <li><a href="{{ route('salaries.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-money-bill-wave text-rose-600 h-5 w-5"></i>Salaries</a>
                        </li>
                        <li><a href="{{ route('trainings.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-chalkboard-teacher text-teal-600 h-5 w-5"></i>Trainings</a>
                        </li>
                    @elseif(auth()->check() && auth()->user()->isUser())
                        @php
                            $user = auth()->user();
                            $isHr = $user->isHr();
                        @endphp
                        @if($isHr)
                            <li><a href="{{ route('employees.index') }}"
                                    class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-users text-emerald-600 h-5 w-5"></i>Employees</a>
                            </li>
                            <li><a href="{{ route('departments.index') }}"
                                    class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-sitemap text-yellow-600 h-5 w-5"></i>Departments</a>
                            </li>
                            <li><a href="{{ route('designations.index') }}"
                                    class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-briefcase text-purple-600 h-5 w-5"></i>Designations</a>
                            </li>
                        @endif
                        <li><a href="{{ route('attendance.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-calendar-check text-pink-600 h-5 w-5"></i>Attendance</a>
                        </li>
                        <li><a href="{{ route('leaves.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-plane-departure text-indigo-600 h-5 w-5"></i>Leaves</a>
                        </li>
                        <li><a href="{{ route('salaries.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-money-bill-wave text-rose-600 h-5 w-5"></i>Salaries</a>
                        </li>
                        <li><a href="{{ route('trainings.index') }}"
                                class="block px-4 py-2 rounded hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2"><i class="fas fa-chalkboard-teacher text-teal-600 h-5 w-5"></i>Trainings</a>
                        </li>
                    @endif
                </ul>
            </nav>
            @if (auth()->check())
                <form method="POST" action="{{ route('logout') }}" class="mt-8 px-2">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded bg-red-500 text-white font-semibold shadow hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            @endif
        </aside>
        <!-- Main content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Main content -->
            <main id="main-content" class="flex-1 w-full max-w-7xl mx-auto px-4 py-8">
                @yield('content')
            </main>
            <footer class="mt-auto py-6 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} cbsiot.live. All rights reserved.
            </footer>
        </div>
    </div>
    @yield('scripts')
</body>

</html>
