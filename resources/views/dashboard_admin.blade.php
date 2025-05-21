@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-4">
    <div class="text-center py-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
        <p class="text-base font-medium">Welcome, {{ auth()->user()->name }} (Admin)!</p>
    </div>
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-2xl shadow-lg border border-blue-200 mt-4">
        <div id="dashboard-stats" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-3" role="region" aria-label="Dashboard Statistics">
            @php
                $stats = [
                    [ 'label' => 'Companies', 'icon' => '🏢', 'color' => 'blue', 'route' => route('companies.index'), 'count' => $stats['companies'] ?? 0 ],
                    [ 'label' => 'Employees', 'icon' => '👤', 'color' => 'green', 'route' => route('employees.index'), 'count' => $stats['employees'] ?? 0 ],
                    [ 'label' => 'Departments', 'icon' => '🏬', 'color' => 'yellow', 'route' => route('departments.index'), 'count' => $stats['departments'] ?? 0 ],
                    [ 'label' => 'Positions', 'icon' => '💼', 'color' => 'purple', 'route' => route('positions.index'), 'count' => $stats['positions'] ?? 0 ],
                    [ 'label' => 'Attendance', 'icon' => '🕒', 'color' => 'pink', 'route' => route('attendance.index'), 'count' => $stats['attendance'] ?? 0 ],
                    [ 'label' => 'Leaves', 'icon' => '🌴', 'color' => 'indigo', 'route' => route('leaves.index'), 'count' => $stats['leaves'] ?? 0 ],
                    [ 'label' => 'Salaries', 'icon' => '💰', 'color' => 'red', 'route' => route('salaries.index'), 'count' => $stats['salaries'] ?? 0 ],
                    [ 'label' => 'Trainings', 'icon' => '📚', 'color' => 'teal', 'route' => route('trainings.index'), 'count' => $stats['trainings'] ?? 0 ],
                    [ 'label' => 'Logins', 'icon' => '🔑', 'color' => 'gray', 'route' => route('logins.index'), 'count' => $stats['logins'] ?? 0 ],
                ];
            @endphp
            @foreach ($stats as $stat)
                <a href="{{ $stat['route'] }}" class="group block bg-white border border-{{ $stat['color'] }}-200 rounded-xl p-3 shadow hover:shadow-xl transition flex flex-col items-center text-center hover:bg-{{ $stat['color'] }}-50 focus:outline-none focus:ring-2 focus:ring-{{ $stat['color'] }}-500" role="link" aria-label="{{ $stat['label'] }} Statistics">
                    <span class="text-2xl mb-1 text-{{ $stat['color'] }}-500 group-hover:text-{{ $stat['color'] }}-700">{{ $stat['icon'] }}</span>
                    <span class="font-semibold text-{{ $stat['color'] }}-900 text-sm">{{ $stat['label'] }}</span>
                    <span class="mt-1 text-xl font-bold text-{{ $stat['color'] }}-700">{{ $stat['count'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
