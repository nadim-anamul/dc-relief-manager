<?php

namespace App\Http\Controllers;

use App\Models\ReliefApplication;
use App\Models\OrganizationType;
use App\Models\Zilla;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use App\Models\ReliefType;
use App\Models\ReliefItem;
use App\Models\ReliefApplicationItem;
use App\Http\Requests\ReliefApplicationRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ReliefApplicationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): View
	{
		$query = ReliefApplication::with(['organizationType', 'zilla', 'upazila', 'union', 'ward', 'reliefType']);

		// Filter by status if provided
		if ($request->filled('status')) {
			$query->where('status', $request->status);
		}

		// Filter by relief type if provided
		if ($request->filled('relief_type_id')) {
			$query->where('relief_type_id', $request->relief_type_id);
		}

		// Filter by organization type if provided
		if ($request->filled('organization_type_id')) {
			$query->where('organization_type_id', $request->organization_type_id);
		}

		// Filter by zilla if provided
		if ($request->filled('zilla_id')) {
			$query->where('zilla_id', $request->zilla_id);
		}

		$reliefApplications = $query->orderBy('created_at', 'desc')->paginate(15);
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();
		$organizationTypes = OrganizationType::ordered()->get();
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();

		return view('relief-applications.index', compact('reliefApplications', 'reliefTypes', 'organizationTypes', 'zillas'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		$organizationTypes = OrganizationType::ordered()->get();
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();
		
		// Get active projects with their relief types and economic years
		$projects = \App\Models\Project::with(['reliefType', 'economicYear'])
			->active() // Only current year projects
			->select('id', 'name', 'relief_type_id', 'economic_year_id')
			->get()
			->map(function($project) {
				return [
					'id' => $project->id,
					'name' => $project->name,
					'relief_type_id' => $project->relief_type_id,
					'relief_type_name' => $project->reliefType->name ?? 'N/A',
					'relief_type_unit' => $project->reliefType->unit ?? 'Unit',
					'relief_type_unit_bn' => $project->reliefType->unit_bn ?? $project->reliefType->unit ?? 'Unit',
					'economic_year' => $project->economicYear->name ?? 'N/A'
				];
			});

		return view('relief-applications.create', compact('organizationTypes', 'zillas', 'reliefTypes', 'projects'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(ReliefApplicationRequest $request): RedirectResponse
	{
		$validated = $request->validated();

		// Handle file upload
		if ($request->hasFile('application_file')) {
			$file = $request->file('application_file');
			$fileName = time() . '_' . $file->getClientOriginalName();
			$filePath = $file->storeAs('relief-applications', $fileName, 'public');
			$validated['application_file'] = $filePath;
		}

		// relief_type_id is now directly provided from the form
		// No need to get it from project since it's selected first

		// Set default values for individual applications
		if ($validated['application_type'] === 'individual') {
			$validated['organization_name'] = null;
			$validated['organization_address'] = null;
			$validated['organization_type_id'] = null;
		}

		// Create the relief application
		$reliefApplication = ReliefApplication::create($validated);

		return redirect()->route('relief-applications.index')
			->with('success', 'Relief application submitted successfully.');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(ReliefApplication $reliefApplication): View
	{
		$reliefApplication->load(['organizationType', 'zilla', 'upazila', 'union', 'ward', 'reliefType']);

		return view('relief-applications.show', compact('reliefApplication'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(ReliefApplication $reliefApplication): View
	{
		$organizationTypes = OrganizationType::ordered()->get();
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		$upazilas = Upazila::where('zilla_id', $reliefApplication->zilla_id)->where('is_active', true)->orderBy('name')->get();
		$unions = Union::where('upazila_id', $reliefApplication->upazila_id)->where('is_active', true)->orderBy('name')->get();
		$wards = Ward::where('union_id', $reliefApplication->union_id)->where('is_active', true)->orderBy('name')->get();
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();
		
		// Get active projects with their relief types and economic years
		$projects = \App\Models\Project::with(['reliefType', 'economicYear'])
			->active() // Only current year projects
			->select('id', 'name', 'relief_type_id', 'economic_year_id')
			->get()
			->map(function($project) {
				return [
					'id' => $project->id,
					'name' => $project->name,
					'relief_type_id' => $project->relief_type_id,
					'relief_type_name' => $project->reliefType->name ?? 'N/A',
					'relief_type_unit' => $project->reliefType->unit ?? 'Unit',
					'relief_type_unit_bn' => $project->reliefType->unit_bn ?? $project->reliefType->unit ?? 'Unit',
					'economic_year' => $project->economicYear->name ?? 'N/A'
				];
			});

		return view('relief-applications.edit', compact('reliefApplication', 'organizationTypes', 'zillas', 'upazilas', 'unions', 'wards', 'reliefTypes', 'projects'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(ReliefApplicationRequest $request, ReliefApplication $reliefApplication): RedirectResponse
	{
		$validated = $request->validated();

		// Handle file upload
		if ($request->hasFile('application_file')) {
			// Delete old file if exists
			if ($reliefApplication->application_file) {
				Storage::disk('public')->delete($reliefApplication->application_file);
			}

			$file = $request->file('application_file');
			$fileName = time() . '_' . $file->getClientOriginalName();
			$filePath = $file->storeAs('relief-applications', $fileName, 'public');
			$validated['application_file'] = $filePath;
		}

		// relief_type_id is now directly provided from the form
		// No need to get it from project since it's selected first

		// Set default values for individual applications
		if ($validated['application_type'] === 'individual') {
			$validated['organization_name'] = null;
			$validated['organization_address'] = null;
			$validated['organization_type_id'] = null;
		}

		// Update the relief application
		$reliefApplication->update($validated);

		return redirect()->route('relief-applications.index')
			->with('success', 'Relief application updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(ReliefApplication $reliefApplication): RedirectResponse
	{
		// Delete file if exists
		if ($reliefApplication->application_file) {
			Storage::disk('public')->delete($reliefApplication->application_file);
		}

		$reliefApplication->delete();

		return redirect()->route('relief-applications.index')
			->with('success', 'Relief application deleted successfully.');
	}

	/**
	 * Get upazilas by zilla ID for dynamic dropdowns.
	 */
	public function getUpazilasByZilla(Zilla $zilla)
	{
		$upazilas = $zilla->upazilas()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'name_bn']);
		return response()->json($upazilas);
	}

	/**
	 * Get unions by upazila ID for dynamic dropdowns.
	 */
	public function getUnionsByUpazila(Upazila $upazila)
	{
		$unions = $upazila->unions()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'name_bn']);
		return response()->json($unions);
	}

	/**
	 * Get wards by union ID for dynamic dropdowns.
	 */
	public function getWardsByUnion(Union $union)
	{
		$wards = $union->wards()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'name_bn']);
		return response()->json($wards);
	}

	/**
	 * Check for duplicate approved applications across all economic years.
	 */
	public function checkDuplicate(Request $request): JsonResponse
	{
		$applicationType = $request->input('application_type');
		$organizationName = $request->input('organization_name');
		$applicantNid = $request->input('applicant_nid');

		$query = ReliefApplication::query()
			->where('status', 'approved');

		if ($applicationType === 'organization' && $organizationName) {
			$query->where('application_type', 'organization')
				->where('organization_name', $organizationName);
		} elseif ($applicationType === 'individual' && $applicantNid) {
			$query->where('application_type', 'individual')
				->where('applicant_nid', $applicantNid);
		} else {
			return response()->json(['duplicate' => false, 'count' => 0]);
		}

		$count = $query->count();
		return response()->json(['duplicate' => $count > 0, 'count' => $count]);
	}

	/**
	 * Get available projects for a relief type.
	 */
	public function getProjectsByReliefType(Request $request): JsonResponse
	{
		$reliefTypeId = $request->input('relief_type_id');
		
		if (!$reliefTypeId) {
			return response()->json([]);
		}

		$projects = \App\Models\Project::where('relief_type_id', $reliefTypeId)
			->whereHas('economicYear', function($query) {
				$query->where('start_date', '<=', now())
					->where('end_date', '>=', now())
					->where('is_current', true);
			})
			->where('allocated_amount', '>', 0)
			->with(['reliefType'])
			->orderBy('name')
			->get()
			->map(function($project) {
				return [
					'id' => $project->id,
					'name' => $project->name,
					'name_bn' => $project->name_bn,
					'relief_type_id' => $project->relief_type_id,
					'relief_type_name' => $project->reliefType->name ?? 'N/A',
					'relief_type_name_bn' => $project->reliefType->name_bn ?? 'N/A',
					'relief_type_unit' => $project->reliefType->unit ?? 'Unit',
					'relief_type_unit_bn' => $project->reliefType->unit_bn ?? $project->reliefType->unit ?? 'Unit',
					'allocated_amount' => $project->allocated_amount,
					'available_amount' => $project->available_amount,
					'economic_year' => $project->economicYear->name ?? 'N/A'
				];
			});

		return response()->json($projects);
	}
}