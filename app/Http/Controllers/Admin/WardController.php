<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ward;
use App\Models\Union;
use App\Models\Upazila;
use App\Models\Zilla;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WardController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): View
	{
		$query = Ward::with(['union.upazila.zilla']);

		// Filter by union if provided
		if ($request->filled('union_id')) {
			$query->where('union_id', $request->union_id);
		}

		$wards = $query->orderBy('name')->paginate(15);
		$unions = Union::with(['upazila.zilla'])->where('is_active', true)->orderBy('name')->get();
		$upazilas = Upazila::with('zilla')->where('is_active', true)->orderBy('name')->get();
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();

		return view('admin.wards.index', compact('wards', 'unions', 'upazilas', 'zillas'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Request $request): View
	{
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		$upazilas = collect();
		$unions = collect();
		
		// If zilla is pre-selected, load its upazilas
		if ($request->filled('zilla_id')) {
			$upazilas = Upazila::where('zilla_id', $request->zilla_id)
				->where('is_active', true)
				->orderBy('name')
				->get();
		}
		
		// If upazila is pre-selected, load its unions
		if ($request->filled('upazila_id')) {
			$unions = Union::where('upazila_id', $request->upazila_id)
				->where('is_active', true)
				->orderBy('name')
				->get();
		}
		
		return view('admin.wards.create', compact('zillas', 'upazilas', 'unions'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'union_id' => 'required|exists:unions,id',
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'is_active' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');

		Ward::create($validated);

		return redirect()->route('admin.wards.index')
			->with('success', __('ওয়ার্ড সফলভাবে তৈরি হয়েছে।'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Ward $ward): View
	{
		$ward->load(['union.upazila.zilla']);
		
		return view('admin.wards.show', compact('ward'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Ward $ward): View
	{
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		$upazilas = Upazila::where('zilla_id', $ward->union->upazila->zilla_id)
			->where('is_active', true)
			->orderBy('name')
			->get();
		$unions = Union::where('upazila_id', $ward->union->upazila_id)
			->where('is_active', true)
			->orderBy('name')
			->get();
		
		return view('admin.wards.edit', compact('ward', 'zillas', 'upazilas', 'unions'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Ward $ward): RedirectResponse
	{
		$validated = $request->validate([
			'union_id' => 'required|exists:unions,id',
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'is_active' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');

		$ward->update($validated);

		return redirect()->route('admin.wards.index')
			->with('success', __('ওয়ার্ড সফলভাবে হালনাগাদ হয়েছে।'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Ward $ward): RedirectResponse
	{
		$ward->delete();

		return redirect()->route('admin.wards.index')
			->with('success', __('ওয়ার্ড সফলভাবে মুছে ফেলা হয়েছে।'));
	}

	/**
	 * Get wards by union for AJAX requests.
	 */
	public function getByUnion(Union $union): JsonResponse
	{
		$wards = $union->wards()
			->where('is_active', true)
			->orderBy('name')
			->get(['id', 'name', 'name_bn']);

		return response()->json($wards);
	}
}