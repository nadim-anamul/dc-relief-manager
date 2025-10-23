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
		$query = ReliefApplication::with(['organizationType', 'zilla', 'upazila', 'union', 'ward', 'reliefType', 'project', 'approvedBy', 'rejectedBy', 'project.economicYear']);

		// Default zilla filter (set to Bogura zilla ID 1)
		$defaultZillaId = 1;
		if (!$request->filled('zilla_id')) {
			$query->where('zilla_id', $defaultZillaId);
		}

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

		// Filter by application type if provided
		if ($request->filled('application_type')) {
			$query->where('application_type', $request->application_type);
		}

		// Filter by zilla if provided
		if ($request->filled('zilla_id')) {
			$query->where('zilla_id', $request->zilla_id);
		}

		// Filter by economic year if provided (but not if project_id is also provided, as project determines economic year)
		if ($request->filled('economic_year_id') && !$request->filled('project_id')) {
			$query->whereHas('project', function($q) use ($request) {
				$q->where('economic_year_id', $request->economic_year_id);
			});
		} elseif (!$request->filled('project_id')) {
			// Default to current economic year only if no specific project is selected
			$currentYear = \App\Models\EconomicYear::where('is_current', true)->first();
			if ($currentYear) {
				$query->whereHas('project', function($q) use ($currentYear) {
					$q->where('economic_year_id', $currentYear->id);
				});
			}
		}

		// Filter by upazila if provided
		if ($request->filled('upazila_id')) {
			$query->where('upazila_id', $request->upazila_id);
		}

		// Filter by union if provided
		if ($request->filled('union_id')) {
			$query->where('union_id', $request->union_id);
		}

		// Filter by project if provided
		if ($request->filled('project_id')) {
			$query->where('project_id', $request->project_id);
		}

		// Search functionality
		if ($request->filled('search')) {
			$searchTerm = $request->get('search');
			$query->where(function($q) use ($searchTerm) {
				$q->where('organization_name', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('subject', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('details', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('applicant_name', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('applicant_designation', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('applicant_phone', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('applicant_address', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('organization_address', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('admin_remarks', 'LIKE', "%{$searchTerm}%")
				  ->orWhereHas('zilla', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('upazila', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('union', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('ward', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('reliefType', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%")
								->orWhere('name_bn', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('organizationType', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('project', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					});
			});
		}

		// Default sorting: pending first, then approved, then rejected
		$query->orderByRaw("CASE 
			WHEN status = 'pending' THEN 1 
			WHEN status = 'approved' THEN 2 
			WHEN status = 'rejected' THEN 3 
			ELSE 4 
		END")
		->orderBy('created_at', 'desc');

		$reliefApplications = $query->paginate(15);

		// Get filter options
		$reliefTypes = \App\Models\ReliefType::where('is_active', true)->ordered()->get();
		$organizationTypes = \App\Models\OrganizationType::ordered()->get();
		$zillas = \App\Models\Zilla::where('is_active', true)->orderBy('name')->get();
		$economicYears = \App\Models\EconomicYear::where('is_active', true)->orderBy('start_date', 'desc')->get();
		
		// Get upazilas for the selected/default zilla
		$selectedZillaId = $request->get('zilla_id') ?: $defaultZillaId;
		$upazilas = \App\Models\Upazila::where('zilla_id', $selectedZillaId)
			->where('is_active', true)
			->orderBy('name')
			->get();

		// Get unions for the selected upazila
		$selectedUpazilaId = $request->get('upazila_id');
		$unions = collect();
		if ($selectedUpazilaId) {
			$unions = \App\Models\Union::where('upazila_id', $selectedUpazilaId)
				->where('is_active', true)
				->orderBy('name')
				->get();
		}

		// Calculate statistics with same filters
		$statsQuery = ReliefApplication::query();
		
		// Apply same filters for statistics
		if (!$request->filled('zilla_id')) {
			$statsQuery->where('zilla_id', $defaultZillaId);
		} else {
			$statsQuery->where('zilla_id', $request->zilla_id);
		}
		if ($request->filled('status')) {
			$statsQuery->where('status', $request->status);
		}
		if ($request->filled('relief_type_id')) {
			$statsQuery->where('relief_type_id', $request->relief_type_id);
		}
		if ($request->filled('organization_type_id')) {
			$statsQuery->where('organization_type_id', $request->organization_type_id);
		}
		if ($request->filled('application_type')) {
			$statsQuery->where('application_type', $request->application_type);
		}
		if ($request->filled('economic_year_id') && !$request->filled('project_id')) {
			$statsQuery->whereHas('project', function($q) use ($request) {
				$q->where('economic_year_id', $request->economic_year_id);
			});
		} elseif (!$request->filled('project_id')) {
			$currentYear = \App\Models\EconomicYear::where('is_current', true)->first();
			if ($currentYear) {
				$statsQuery->whereHas('project', function($q) use ($currentYear) {
					$q->where('economic_year_id', $currentYear->id);
				});
			}
		}
		if ($request->filled('upazila_id')) {
			$statsQuery->where('upazila_id', $request->upazila_id);
		}
		if ($request->filled('union_id')) {
			$statsQuery->where('union_id', $request->union_id);
		}
		if ($request->filled('project_id')) {
			$statsQuery->where('project_id', $request->project_id);
		}
		
		// Apply search filter to statistics
		if ($request->filled('search')) {
			$searchTerm = $request->get('search');
			$statsQuery->where(function($q) use ($searchTerm) {
				$q->where('organization_name', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('subject', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('details', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('applicant_name', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('applicant_designation', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('applicant_phone', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('applicant_address', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('organization_address', 'LIKE', "%{$searchTerm}%")
				  ->orWhere('admin_remarks', 'LIKE', "%{$searchTerm}%")
				  ->orWhereHas('zilla', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('upazila', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('union', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('ward', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('reliefType', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%")
								->orWhere('name_bn', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('organizationType', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					})
				  ->orWhereHas('project', function($subQuery) use ($searchTerm) {
						$subQuery->where('name', 'LIKE', "%{$searchTerm}%");
					});
			});
		}

		$totalApplications = $statsQuery->count();
		$pendingApplications = $statsQuery->clone()->where('status', 'pending')->count();
		$approvedApplications = $statsQuery->clone()->where('status', 'approved')->count();
		$rejectedApplications = $statsQuery->clone()->where('status', 'rejected')->count();
		$totalApprovedAmount = $statsQuery->clone()->where('status', 'approved')->sum('approved_amount');

		return view('admin.relief-applications.index', compact(
			'reliefApplications',
			'reliefTypes',
			'organizationTypes',
			'zillas',
			'economicYears',
			'upazilas',
			'unions',
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

		[$hasDuplicate, $duplicateCount] = $this->getDuplicateApprovedInCurrentYear($reliefApplication);

		return view('admin.relief-applications.show', compact('reliefApplication', 'hasDuplicate', 'duplicateCount'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(ReliefApplication $reliefApplication): View
	{
		$reliefApplication->load(['organizationType', 'zilla', 'upazila', 'union', 'ward', 'reliefType', 'project', 'approvedBy', 'rejectedBy']);

		[$hasDuplicate, $duplicateCount] = $this->getDuplicateApprovedInCurrentYear($reliefApplication);

		return view('admin.relief-applications.edit', compact('reliefApplication', 'hasDuplicate', 'duplicateCount'));
	}

	/**
	 * Determine if there are duplicate approved applications in the current economic year
	 * for the same organization name (organization type) or same NID (individual type).
	 */
	private function getDuplicateApprovedInCurrentYear(ReliefApplication $application): array
	{
		$currentYear = \App\Models\EconomicYear::where('is_current', true)->first();
		if (!$currentYear) {
			return [false, 0];
		}

		$query = ReliefApplication::query()
			->where('status', 'approved')
			->whereKeyNot($application->getKey())
			->whereHas('project', function($q) use ($currentYear) {
				$q->where('economic_year_id', $currentYear->id);
			});

		if ($application->application_type === 'organization' && $application->organization_name) {
			$query->where('application_type', 'organization')
				->where('organization_name', $application->organization_name);
		} elseif ($application->application_type === 'individual' && $application->applicant_nid) {
			$query->where('application_type', 'individual')
				->where('applicant_nid', $application->applicant_nid);
		} else {
			return [false, 0];
		}

		$count = $query->count();
		return [$count > 0, $count];
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

	/**
	 * Get upazilas by zilla.
	 */
	public function getUpazilasByZilla(Request $request): JsonResponse
	{
		\Log::info('getUpazilasByZilla called', ['request' => $request->all(), 'user' => auth()->user()?->id]);
		
		$zillaId = $request->input('zilla_id');
		
		if (!$zillaId) {
			\Log::info('No zilla_id provided');
			return response()->json([]);
		}

		$upazilas = \App\Models\Upazila::where('zilla_id', $zillaId)
			->where('is_active', true)
			->orderBy('name')
			->get(['id', 'name', 'name_bn']);

		\Log::info('Returning upazilas', ['count' => $upazilas->count(), 'zilla_id' => $zillaId]);
		return response()->json($upazilas);
	}

	/**
	 * Get unions by upazila.
	 */
	public function getUnionsByUpazila(Request $request): JsonResponse
	{
		\Log::info('getUnionsByUpazila called', ['request' => $request->all(), 'user' => auth()->user()?->id]);
		
		$upazilaId = $request->input('upazila_id');
		
		if (!$upazilaId) {
			\Log::info('No upazila_id provided');
			return response()->json([]);
		}

		$unions = \App\Models\Union::where('upazila_id', $upazilaId)
			->where('is_active', true)
			->orderBy('name')
			->get(['id', 'name', 'name_bn']);

		\Log::info('Returning unions', ['count' => $unions->count(), 'upazila_id' => $upazilaId]);
		return response()->json($unions);
	}
}