<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $trainings = Training::all();
        return view('trainings.index', compact('trainings'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('trainings.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
        ]);
        Training::create($validated);
        return redirect()->route('trainings.index')->with('success', 'Training created successfully.');
    }

    // Show the form for editing the specified resource.
    public function edit(Training $training)
    {
        return view('trainings.edit', compact('training'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
        ]);
        $training->update($validated);
        return redirect()->route('trainings.index')->with('success', 'Training updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('trainings.index')->with('success', 'Training deleted successfully.');
    }
}
