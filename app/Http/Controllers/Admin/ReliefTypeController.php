<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReliefType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReliefTypeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		$reliefTypes = ReliefType::ordered()
			->paginate(15);

		return view('admin.relief-types.index', compact('reliefTypes'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		return view('admin.relief-types.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'description' => 'nullable|string',
			'description_bn' => 'nullable|string',
			'unit' => 'nullable|string|max:50',
			'unit_bn' => 'nullable|string|max:50',
			'color_code' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
			'is_active' => 'boolean',
			'sort_order' => 'nullable|integer|min:0',
		]);

		$validated['is_active'] = $request->has('is_active');
		$validated['sort_order'] = $validated['sort_order'] ?? ReliefType::max('sort_order') + 1;

		ReliefType::create($validated);

		return redirect()->route('admin.relief-types.index')
			->with('success', 'Relief Type created successfully.');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(ReliefType $reliefType): View
	{
		$reliefType->load(['projects.economicYear']);
		
		return view('admin.relief-types.show', compact('reliefType'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(ReliefType $reliefType): View
	{
		return view('admin.relief-types.edit', compact('reliefType'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, ReliefType $reliefType): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'description' => 'nullable|string',
			'description_bn' => 'nullable|string',
			'unit' => 'nullable|string|max:50',
			'unit_bn' => 'nullable|string|max:50',
			'color_code' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
			'is_active' => 'boolean',
			'sort_order' => 'nullable|integer|min:0',
		]);

		$validated['is_active'] = $request->has('is_active');

		$reliefType->update($validated);

		return redirect()->route('admin.relief-types.index')
			->with('success', 'Relief Type updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(ReliefType $reliefType): RedirectResponse
	{
		$reliefType->delete();

		return redirect()->route('admin.relief-types.index')
			->with('success', 'Relief Type deleted successfully.');
	}
}