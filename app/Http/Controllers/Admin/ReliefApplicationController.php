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
			'project_id' => 'nullable|exists:projects,id',
			'admin_remarks' => 'nullable|string',
			'items' => 'nullable|array',
			'items.*.quantity_approved' => 'nullable|numeric|min:0',
			'items.*.unit_price' => 'nullable|numeric|min:0',
			'items.*.relief_item_id' => 'required|exists:relief_items,id',
		]);

		try {
			if ($validated['status'] === 'approved') {
				if (!$validated['project_id']) {
					return redirect()->back()
						->withErrors(['error' => 'Project selection is required for approval.'])
						->withInput();
				}

				// Calculate total approved amount from items
				$totalApprovedAmount = 0;
				if ($request->has('items')) {
					foreach ($request->items as $item) {
						$quantityApproved = floatval($item['quantity_approved'] ?? 0);
						$unitPrice = floatval($item['unit_price'] ?? 0);
						$totalApprovedAmount += $quantityApproved * $unitPrice;
					}
				}

				// Update relief application items with approved quantities
				if ($request->has('items')) {
					foreach ($request->items as $item) {
						$reliefApplicationItem = $reliefApplication->reliefApplicationItems()
							->where('relief_item_id', $item['relief_item_id'])
							->first();

						if ($reliefApplicationItem) {
							$quantityApproved = floatval($item['quantity_approved'] ?? 0);
							$unitPrice = floatval($item['unit_price'] ?? 0);
							$totalAmount = $quantityApproved * $unitPrice;

							$reliefApplicationItem->update([
								'quantity_approved' => $quantityApproved,
								'unit_price' => $unitPrice,
								'total_amount' => $totalAmount,
							]);
						}
					}
				}

				$reliefApplication->approve(
					$totalApprovedAmount,
					$validated['project_id'],
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
			->where('start_date', '<=', now())
			->where('end_date', '>=', now())
			->where('budget', '>', 0)
			->orderBy('name')
			->get(['id', 'name', 'budget', 'formatted_budget']);

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
			'budget' => $project->budget,
			'formatted_budget' => $project->formatted_budget
		]);
	}
}