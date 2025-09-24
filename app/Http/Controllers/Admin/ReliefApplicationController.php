<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReliefApplication;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ReliefApplicationController extends Controller
{
	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): RedirectResponse
	{
		// Redirect to the regular relief application create form
		return redirect()->route('relief-applications.create');
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): View
	{
		$query = ReliefApplication::with(['organizationType', 'zilla', 'upazila', 'union', 'ward', 'reliefType', 'project', 'approvedBy', 'rejectedBy']);

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

		// Get filter options
		$reliefTypes = \App\Models\ReliefType::where('is_active', true)->ordered()->get();
		$organizationTypes = \App\Models\OrganizationType::ordered()->get();
		$zillas = \App\Models\Zilla::where('is_active', true)->orderBy('name')->get();

		// Calculate statistics
		$totalApplications = ReliefApplication::count();
		$pendingApplications = ReliefApplication::where('status', 'pending')->count();
		$approvedApplications = ReliefApplication::where('status', 'approved')->count();
		$rejectedApplications = ReliefApplication::where('status', 'rejected')->count();
		$totalApprovedAmount = ReliefApplication::where('status', 'approved')->sum('approved_amount');

		return view('admin.relief-applications.index', compact(
			'reliefApplications',
			'reliefTypes',
			'organizationTypes',
			'zillas',
			'totalApplications',
			'pendingApplications',
			'approvedApplications',
			'rejectedApplications',
			'totalApprovedAmount'
		));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(ReliefApplication $reliefApplication): View
	{
		$reliefApplication->load(['organizationType', 'zilla', 'upazila', 'union', 'ward', 'reliefType', 'project', 'approvedBy', 'rejectedBy']);

		return view('admin.relief-applications.show', compact('reliefApplication'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(ReliefApplication $reliefApplication): View
	{
		$reliefApplication->load(['organizationType', 'zilla', 'upazila', 'union', 'ward', 'reliefType', 'project', 'approvedBy', 'rejectedBy']);

		return view('admin.relief-applications.edit', compact('reliefApplication'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, ReliefApplication $reliefApplication): RedirectResponse
	{
		$validated = $request->validate([
			'status' => 'required|in:pending,approved,rejected',
			'approved_amount' => 'nullable|numeric|min:0',
			'admin_remarks' => 'nullable|string',
		]);

		try {
			if ($validated['status'] === 'approved') {
				if (!$reliefApplication->project_id) {
					return redirect()->back()
						->withErrors(['error' => 'Application must be assigned to a project before approval.'])
						->withInput();
				}

				if (!$validated['approved_amount'] || $validated['approved_amount'] <= 0) {
					return redirect()->back()
						->withErrors(['error' => 'Approval amount is required and must be greater than 0.'])
						->withInput();
				}

				// Check if project has sufficient budget
				$project = $reliefApplication->project;
				if ($project->available_amount < $validated['approved_amount']) {
					return redirect()->back()
						->withErrors(['error' => 'Insufficient project budget. Available: ' . $project->formatted_available_amount])
						->withInput();
				}

				$reliefApplication->approve(
					$validated['approved_amount'],
					$reliefApplication->project_id,
					$validated['admin_remarks'],
					auth()->id()
				);

				$message = 'Application approved successfully. Budget deducted from project.';
			} elseif ($validated['status'] === 'rejected') {
				$reliefApplication->reject(
					$validated['admin_remarks'],
					auth()->id()
				);

				$message = 'Application rejected successfully.';
			} else {
				// Reset to pending
				$reliefApplication->update([
					'status' => 'pending',
					'approved_amount' => null,
					'project_id' => null,
					'admin_remarks' => $validated['admin_remarks'],
					'approved_by' => null,
					'approved_at' => null,
					'rejected_by' => null,
					'rejected_at' => null,
				]);

				// Reset approved quantities for all items
				$reliefApplication->reliefApplicationItems()->update([
					'quantity_approved' => null,
					'total_amount' => null,
				]);

				$message = 'Application status updated successfully.';
			}

			return redirect()->route('admin.relief-applications.index')
				->with('success', $message);

		} catch (\Exception $e) {
			return redirect()->back()
				->withErrors(['error' => $e->getMessage()])
				->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(ReliefApplication $reliefApplication): RedirectResponse
	{
		// Delete file if exists
		if ($reliefApplication->application_file) {
			\Illuminate\Support\Facades\Storage::disk('public')->delete($reliefApplication->application_file);
		}

		$reliefApplication->delete();

		return redirect()->route('admin.relief-applications.index')
			->with('success', 'Relief application deleted successfully.');
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

		$projects = Project::where('relief_type_id', $reliefTypeId)
			->whereHas('economicYear', function($query) {
				$query->where('start_date', '<=', now())
					->where('end_date', '>=', now())
					->where('is_current', true);
			})
			->where('allocated_amount', '>', 0)
			->orderBy('name')
			->get(['id', 'name', 'allocated_amount', 'formatted_allocated_amount']);

		return response()->json($projects);
	}

	/**
	 * Get project budget information.
	 */
	public function getProjectBudget(Request $request): JsonResponse
	{
		$projectId = $request->input('project_id');
		
		if (!$projectId) {
			return response()->json(['budget' => 0, 'formatted_budget' => '৳0.00']);
		}

		$project = Project::find($projectId);
		
		if (!$project) {
			return response()->json(['budget' => 0, 'formatted_budget' => '৳0.00']);
		}

		return response()->json([
			'budget' => $project->allocated_amount,
			'formatted_budget' => $project->formatted_allocated_amount
		]);
	}
}