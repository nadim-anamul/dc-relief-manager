<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Union;
use App\Models\Upazila;
use App\Models\Zilla;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UnionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): View
	{
		$query = Union::with(['upazila.zilla'])->withCount(['wards']);

		// Filter by upazila if provided
		if ($request->filled('upazila_id')) {
			$query->where('upazila_id', $request->upazila_id);
		}

		$unions = $query->orderBy('name')->paginate(15);
		$upazilas = Upazila::with('zilla')->where('is_active', true)->orderBy('name')->get();
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();

		return view('admin.unions.index', compact('unions', 'upazilas', 'zillas'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Request $request): View
	{
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		$upazilas = collect();
		
		// If zilla is pre-selected, load its upazilas
		if ($request->filled('zilla_id')) {
			$upazilas = Upazila::where('zilla_id', $request->zilla_id)
				->where('is_active', true)
				->orderBy('name')
				->get();
		}
		
		return view('admin.unions.create', compact('zillas', 'upazilas'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'upazila_id' => 'required|exists:upazilas,id',
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'code' => 'required|string|max:20|unique:unions,code',
			'is_active' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');

		Union::create($validated);

		return redirect()->route('admin.unions.index')
			->with('success', 'Union created successfully.');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Union $union): View
	{
		$union->load(['upazila.zilla', 'wards']);
		
		return view('admin.unions.show', compact('union'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Union $union): View
	{
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		$upazilas = Upazila::where('zilla_id', $union->upazila->zilla_id)
			->where('is_active', true)
			->orderBy('name')
			->get();
		
		return view('admin.unions.edit', compact('union', 'zillas', 'upazilas'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Union $union): RedirectResponse
	{
		$validated = $request->validate([
			'upazila_id' => 'required|exists:upazilas,id',
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'code' => 'required|string|max:20|unique:unions,code,' . $union->id,
			'is_active' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');

		$union->update($validated);

		return redirect()->route('admin.unions.index')
			->with('success', 'Union updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Union $union): RedirectResponse
	{
		$union->delete();

		return redirect()->route('admin.unions.index')
			->with('success', 'Union deleted successfully.');
	}

	/**
	 * Get unions by upazila for AJAX requests.
	 */
	public function getByUpazila(Upazila $upazila): JsonResponse
	{
		$unions = $upazila->unions()
			->where('is_active', true)
			->orderBy('name')
			->get(['id', 'name', 'name_bn']);

		return response()->json($unions);
	}
}