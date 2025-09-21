<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EconomicYear;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EconomicYearController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		$economicYears = EconomicYear::orderBy('start_date', 'desc')
			->paginate(15);

		return view('admin.economic-years.index', compact('economicYears'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		return view('admin.economic-years.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'start_date' => 'required|date',
			'end_date' => 'required|date|after:start_date',
			'is_active' => 'boolean',
			'is_current' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');
		$validated['is_current'] = $request->has('is_current');

		// If setting as current, unset other current years
		if ($validated['is_current']) {
			EconomicYear::where('is_current', true)->update(['is_current' => false]);
		}

		EconomicYear::create($validated);

		return redirect()->route('admin.economic-years.index')
			->with('success', 'Economic Year created successfully.');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(EconomicYear $economicYear): View
	{
		// $economicYear->load(['reliefRequests']); // Will be enabled when ReliefRequest model is created
		
		return view('admin.economic-years.show', compact('economicYear'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(EconomicYear $economicYear): View
	{
		return view('admin.economic-years.edit', compact('economicYear'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, EconomicYear $economicYear): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'start_date' => 'required|date',
			'end_date' => 'required|date|after:start_date',
			'is_active' => 'boolean',
			'is_current' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');
		$validated['is_current'] = $request->has('is_current');

		// If setting as current, unset other current years
		if ($validated['is_current']) {
			EconomicYear::where('is_current', true)
				->where('id', '!=', $economicYear->id)
				->update(['is_current' => false]);
		}

		$economicYear->update($validated);

		return redirect()->route('admin.economic-years.index')
			->with('success', 'Economic Year updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(EconomicYear $economicYear): RedirectResponse
	{
		// Prevent deletion if it's the current year
		if ($economicYear->is_current) {
			return redirect()->route('admin.economic-years.index')
				->with('error', 'Cannot delete the current economic year.');
		}

		$economicYear->delete();

		return redirect()->route('admin.economic-years.index')
			->with('success', 'Economic Year deleted successfully.');
	}
}