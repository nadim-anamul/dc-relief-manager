<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrganizationTypeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		$organizationTypes = OrganizationType::ordered()->paginate(15);

		return view('admin.organization-types.index', compact('organizationTypes'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		return view('admin.organization-types.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255|unique:organization_types,name',
			'description' => 'nullable|string',
		]);

		OrganizationType::create($validated);

		return redirect()->route('admin.organization-types.index')
			->with('success', __('সংস্থার ধরন সফলভাবে তৈরি হয়েছে।'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(OrganizationType $organizationType): View
	{
		return view('admin.organization-types.show', compact('organizationType'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(OrganizationType $organizationType): View
	{
		return view('admin.organization-types.edit', compact('organizationType'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, OrganizationType $organizationType): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255|unique:organization_types,name,' . $organizationType->id,
			'description' => 'nullable|string',
		]);

		$organizationType->update($validated);

		return redirect()->route('admin.organization-types.index')
			->with('success', __('সংস্থার ধরন সফলভাবে হালনাগাদ হয়েছে।'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(OrganizationType $organizationType): RedirectResponse
	{
		$organizationType->delete();

		return redirect()->route('admin.organization-types.index')
			->with('success', __('সংস্থার ধরন সফলভাবে মুছে ফেলা হয়েছে।'));
	}
}