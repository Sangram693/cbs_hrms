@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-12">
    <div class="text-center py-12 bg-gradient-to-r from-indigo-500 to-blue-600 text-white rounded-lg shadow-md">
        <h1 class="text-4xl font-bold mb-4">User Dashboard</h1>
        <p class="text-lg font-medium">Welcome, {{ auth()->user()->name }}!</p>
    </div>
    <div class="bg-gradient-to-br from-indigo-50 to-blue-100 p-10 rounded-2xl shadow-lg border border-indigo-200 mt-8">
        <div class="text-center">
            <p class="text-lg">Here you can view your attendance, leaves, salary, and trainings.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-8">
            <a href="{{ route('attendance.index') }}" class="block bg-white border border-pink-200 rounded-xl p-6 shadow hover:shadow-xl transition flex flex-col items-center text-center hover:bg-pink-50 focus:outline-none focus:ring-2 focus:ring-pink-500">
                <span class="text-4xl mb-2 text-pink-500">🕒</span>
                <span class="font-semibold text-pink-900">Attendance</span>
            </a>
            <a href="{{ route('leaves.index') }}" class="block bg-white border border-indigo-200 rounded-xl p-6 shadow hover:shadow-xl transition flex flex-col items-center text-center hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="text-4xl mb-2 text-indigo-500">🌴</span>
                <span class="font-semibold text-indigo-900">Leaves</span>
            </a>
            <a href="{{ route('salaries.index') }}" class="block bg-white border border-red-200 rounded-xl p-6 shadow hover:shadow-xl transition flex flex-col items-center text-center hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500">
                <span class="text-4xl mb-2 text-red-500">💰</span>
                <span class="font-semibold text-red-900">Salaries</span>
            </a>
            <a href="{{ route('trainings.index') }}" class="block bg-white border border-teal-200 rounded-xl p-6 shadow hover:shadow-xl transition flex flex-col items-center text-center hover:bg-teal-50 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <span class="text-4xl mb-2 text-teal-500">📚</span>
                <span class="font-semibold text-teal-900">Trainings</span>
            </a>
        </div>
    </div>
</div>
@endsection
