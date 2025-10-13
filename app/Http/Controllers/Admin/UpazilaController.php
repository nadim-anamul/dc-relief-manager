<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upazila;
use App\Models\Zilla;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UpazilaController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): View
	{
		$query = Upazila::with(['zilla'])->withCount(['unions', 'wards']);

		// Filter by zilla if provided
		if ($request->filled('zilla_id')) {
			$query->where('zilla_id', $request->zilla_id);
		}

		$upazilas = $query->orderBy('name')->paginate(15);
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();

		return view('admin.upazilas.index', compact('upazilas', 'zillas'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		
		return view('admin.upazilas.create', compact('zillas'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'zilla_id' => 'required|exists:zillas,id',
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'code' => 'required|string|max:20|unique:upazilas,code',
			'is_active' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');

		Upazila::create($validated);

		return redirect()->route('admin.upazilas.index')
			->with('success', __('উপজেলা সফলভাবে তৈরি হয়েছে।'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Upazila $upazila): View
	{
		$upazila->load(['zilla', 'unions.wards']);
		
		return view('admin.upazilas.show', compact('upazila'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Upazila $upazila): View
	{
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		
		return view('admin.upazilas.edit', compact('upazila', 'zillas'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Upazila $upazila): RedirectResponse
	{
		$validated = $request->validate([
			'zilla_id' => 'required|exists:zillas,id',
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'code' => 'required|string|max:20|unique:upazilas,code,' . $upazila->id,
			'is_active' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');

		$upazila->update($validated);

		return redirect()->route('admin.upazilas.index')
			->with('success', __('উপজেলা সফলভাবে হালনাগাদ হয়েছে।'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Upazila $upazila): RedirectResponse
	{
		$upazila->delete();

		return redirect()->route('admin.upazilas.index')
			->with('success', __('উপজেলা সফলভাবে মুছে ফেলা হয়েছে।'));
	}

	/**
	 * Get upazilas by zilla for AJAX requests.
	 */
	public function getByZilla(Zilla $zilla): JsonResponse
	{
        $upazilas = $zilla->upazilas()
			->where('is_active', true)
			->orderBy('name')
            ->get(['id', 'name', 'name_bn'])
            ->map(function ($u) {
                $u->name_display = app()->isLocale('bn') ? ($u->name_bn ?: $u->name) : ($u->name ?: $u->name_bn);
                return $u;
            });

		return response()->json($upazilas);
	}
}