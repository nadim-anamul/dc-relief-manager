<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zilla;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ZillaController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		$zillas = Zilla::withCount(['upazilas', 'unions', 'wards'])
			->orderBy('name')
			->paginate(15);

		return view('admin.zillas.index', compact('zillas'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		return view('admin.zillas.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'is_active' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');

		Zilla::create($validated);

		return redirect()->route('admin.zillas.index')
			->with('success', __('জেলা সফলভাবে তৈরি হয়েছে।'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Zilla $zilla): View
	{
		$zilla->load(['upazilas.unions.wards']);
		
		return view('admin.zillas.show', compact('zilla'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Zilla $zilla): View
	{
		return view('admin.zillas.edit', compact('zilla'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Zilla $zilla): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'name_bn' => 'nullable|string|max:255',
			'is_active' => 'boolean',
		]);

		$validated['is_active'] = $request->has('is_active');

		$zilla->update($validated);

		return redirect()->route('admin.zillas.index')
			->with('success', __('জেলা সফলভাবে হালনাগাদ হয়েছে।'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Zilla $zilla): RedirectResponse
	{
		$zilla->delete();

		return redirect()->route('admin.zillas.index')
			->with('success', __('জেলা সফলভাবে মুছে ফেলা হয়েছে।'));
	}
}