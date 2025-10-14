<?php

namespace App\Http\Controllers;

use App\Models\ReliefApplication;
use App\Models\Project;
use App\Models\Zilla;
use App\Models\OrganizationType;
use App\Models\ReliefType;
use App\Models\EconomicYear;
use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	/**
	 * Display the dashboard.
	 */
    public function index(Request $request): View
	{
		$years = EconomicYear::orderByDesc('start_date')->get();
		$selectedYear = $this->resolveSelectedYear($request, $years);
		$selectedZillaId = $request->integer('zilla_id');
		$sort = $request->get('sort');

		// Load zillas and apply default when exactly one exists
		$zillas = Zilla::orderBy('name')->get(['id','name','name_bn']);
		if (!$selectedZillaId && $zillas->count() === 1) {
			$selectedZillaId = $zillas->first()->id;
		}
		
        // Pagination parameters
        $pageSize = 15;
        $upazilaPage = $request->integer('upazila_page', 1);
        $upazilaUnionPage = $request->integer('upazila_union_page', 1);
        $unionSummaryPage = $request->integer('union_summary_page', 1);
        
		$stats = $this->getDashboardStatistics($selectedYear, $selectedZillaId, $sort, $pageSize, $upazilaPage, $upazilaUnionPage, $unionSummaryPage);
		$chartData = $this->getChartData($selectedYear);
		
		return view('dashboard', [
			'stats' => $stats,
			'chartData' => $chartData,
			'years' => $years,
            'selectedYearId' => $selectedYear?->id,
			'zillas' => $zillas,
			'selectedZillaId' => $selectedZillaId,
            'currentSort' => $sort,
            'pageSize' => $pageSize,
		]);
	}

	/**
	 * Get dashboard statistics.
	 */
    private function getDashboardStatistics(?EconomicYear $year = null, ?int $selectedZillaId = null, ?string $sort = null, int $pageSize = 15, int $upazilaPage = 1, int $upazilaUnionPage = 1, int $unionSummaryPage = 1): array
	{
		$start = $year?->start_date;
		$end = $year?->end_date;
		
        $applyDateRange = function ($query, $column = 'created_at') use ($start, $end) {
			if ($start && $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                $query->whereBetween($column, [$s, $e]);
			}
			return $query;
		};
		
		// Total relief distributed
		$totalReliefDistributed = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')->sum('approved_amount');
		
		// Total applications
		$totalApplications = $applyDateRange(ReliefApplication::query())->count();
		$pendingApplications = $applyDateRange(ReliefApplication::where('status', 'pending'))->count();
		$approvedApplications = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')->count();
		$rejectedApplications = $applyDateRange(ReliefApplication::where('status', 'rejected'))
			->count();
		
		// Project statistics - updated to use new scopes and current economic year logic
		if ($year) {
			$totalProjects = Project::forEconomicYear($year->id)->count();
			$activeProjects = $totalProjects;
			$completedProjects = 0;
			$upcomingProjects = 0;
			$totalAllocatedAmount = Project::forEconomicYear($year->id)->sum('allocated_amount');
			$currentYearAllocatedAmount = $totalAllocatedAmount;
		} else {
			$totalProjects = Project::count();
			$activeProjects = Project::active()->count();
			$completedProjects = Project::completed()->count();
			$upcomingProjects = Project::upcoming()->count();
			$totalAllocatedAmount = Project::sum('allocated_amount');
			$currentYearAllocatedAmount = Project::active()->sum('allocated_amount');
		}
		
		// Area-wise statistics
		$areaWiseStats = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->join('zillas', 'relief_applications.zilla_id', '=', 'zillas.id')
			->select('zillas.name as zilla_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'), DB::raw('COUNT(*) as application_count'))
			->groupBy('zillas.id', 'zillas.name')
			->orderBy('total_amount', 'desc')
			->get();
		
		// Organization type statistics
		$orgTypeStats = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->leftJoin('organization_types', 'relief_applications.organization_type_id', '=', 'organization_types.id')
			->select('organization_types.name as org_type_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'), DB::raw('COUNT(*) as application_count'))
			->groupBy('organization_types.id', 'organization_types.name')
			->orderBy('total_amount', 'desc')
			->get();
		
		// Relief type statistics
		$reliefTypeStats = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->join('relief_types', 'relief_applications.relief_type_id', '=', 'relief_types.id')
			->select(
				DB::raw(app()->isLocale('bn') ? 'COALESCE(relief_types.name_bn, relief_types.name) as relief_type_name' : 'COALESCE(relief_types.name, relief_types.name_bn) as relief_type_name'),
				'relief_types.color_code', 
				DB::raw('SUM(relief_applications.approved_amount) as total_amount'), 
				DB::raw('COUNT(*) as application_count')
			)
			->groupBy('relief_types.id', 'relief_types.name', 'relief_types.name_bn', 'relief_types.color_code')
			->orderBy('total_amount', 'desc')
			->get();
		
        // Project allocated amounts for selected/current year (top 10)
        $projectAllocationStats = ($year ? Project::forEconomicYear($year->id) : Project::active())
            ->select('name', 'allocated_amount', 'relief_type_id', 'economic_year_id')
            ->with(['reliefType', 'economicYear'])
            ->orderBy('allocated_amount', 'desc')
            ->take(10)
            ->get()
            ->map(function ($p) use ($year) {
                // Build a display string for the economic year with Bangla digits if locale is bn
                if ($p->economicYear) {
                    $display = $p->economicYear->name
                        ?: (bn_number($p->economicYear->start_date?->format('Y')) . ' - ' . bn_number($p->economicYear->end_date?->format('Y')));
                } elseif ($year) {
                    $display = $year->name ?: (bn_number(\Carbon\Carbon::parse($year->start_date)->format('Y')) . ' - ' . bn_number(\Carbon\Carbon::parse($year->end_date)->format('Y')));
                } else {
                    $display = null;
                }

                // Convert digits to Bangla if available helper exists
                if (function_exists('bn_number') && $display) {
                    $p->economic_year_display = bn_number($display);
                } else {
                    $p->economic_year_display = $display;
                }

                return $p;
            });

        // Relief type allocation statistics for selected/current year, including available and used
        $reliefTypeAllocationStats = ($year ? Project::forEconomicYear($year->id) : Project::active())
            ->selectRaw('relief_type_id, SUM(allocated_amount) as total_allocated, SUM(available_amount) as total_available, COUNT(*) as project_count')
			->with('reliefType')
			->groupBy('relief_type_id')
			->orderBy('total_allocated', 'desc')
			->get()
            ->map(function($stat) {
                $unit = $stat->reliefType?->unit_bn ?? $stat->reliefType?->unit ?? '';
                $allocated = (float)($stat->total_allocated ?? 0);
                $available = (float)($stat->total_available ?? 0);
                $used = max(0.0, $allocated - $available);

                $format = function ($n) use ($unit) {
                    $amount = number_format((float)$n, 2);
                    return in_array($unit, ['টাকা', 'Taka']) ? ('৳' . $amount) : ($amount . ' ' . $unit);
                };

                $stat->formatted_allocated = $format($allocated);
                $stat->formatted_available = $format($available);
                $stat->formatted_used = $format($used);
                $stat->used_ratio = $allocated > 0 ? round($used / $allocated, 4) : 0;
                return $stat;
            });

        // Apply optional sort for dashboard widget
        if ($sort === 'used') {
            $reliefTypeAllocationStats = $reliefTypeAllocationStats->sortByDesc('used_ratio')->values();
        } elseif ($sort === 'available') {
            $reliefTypeAllocationStats = $reliefTypeAllocationStats->sortByDesc('total_available')->values();
        } elseif ($sort === 'allocated') {
            $reliefTypeAllocationStats = $reliefTypeAllocationStats->sortByDesc('total_allocated')->values();
        }

        // Inventory-related statistics removed from dashboard

		// Project × Zilla distribution (approved amounts)
		$projectAreaDistribution = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->selectRaw('project_id, zilla_id, SUM(approved_amount) as total_amount, COUNT(*) as application_count')
			->groupBy('project_id', 'zilla_id')
			->get();

		// Project × Upazila distribution (optional filter by zilla)
		$projectUpazilaDistributionQuery = ReliefApplication::where('status', 'approved');
		
		// Filter by economic year through project relationship
		if ($year) {
			$projectUpazilaDistributionQuery->whereHas('project', function($q) use ($year) {
				$q->where('economic_year_id', $year->id);
			});
		}
		
		if ($selectedZillaId) {
			$projectUpazilaDistributionQuery->where('zilla_id', $selectedZillaId);
		}
		$projectUpazilaDistribution = $projectUpazilaDistributionQuery
			->selectRaw('project_id, upazila_id, SUM(approved_amount) as total_amount, COUNT(*) as application_count')
			->groupBy('project_id', 'upazila_id')
			->get();

		// Project × Upazila × Union distribution (optional filter by zilla)
		$projectUpazilaUnionDistributionQuery = ReliefApplication::where('status', 'approved');
		
		// Filter by economic year through project relationship
		if ($year) {
			$projectUpazilaUnionDistributionQuery->whereHas('project', function($q) use ($year) {
				$q->where('economic_year_id', $year->id);
			});
		}
		
		if ($selectedZillaId) {
			$projectUpazilaUnionDistributionQuery->where('zilla_id', $selectedZillaId);
		}
		$projectUpazilaUnionDistribution = $projectUpazilaUnionDistributionQuery
			->selectRaw('project_id, upazila_id, union_id, SUM(approved_amount) as total_amount, COUNT(*) as application_count')
			->groupBy('project_id', 'upazila_id', 'union_id')
			->get();

        // Coverage: unserved and underserved (by zilla)
		$zillaIds = Zilla::pluck('id');
		$distByZilla = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->selectRaw('zilla_id, SUM(approved_amount) as total_amount')
			->groupBy('zilla_id')
			->pluck('total_amount', 'zilla_id');
		$unservedZillaIds = $zillaIds->filter(fn($id) => ($distByZilla[$id] ?? 0) <= 0)->values();
		$amounts = collect($distByZilla->values())->sort()->values();
		$quartileIndex = max(0, (int) floor(0.25 * ($amounts->count() - 1)));
		$underservedThreshold = $amounts->count() > 0 ? $amounts[$quartileIndex] : 0;
		$underservedZillaIds = $zillaIds->filter(function($id) use ($distByZilla, $underservedThreshold) {
			return ($distByZilla[$id] ?? 0) <= $underservedThreshold && ($distByZilla[$id] ?? 0) > 0;
		})->values();

        // If a zilla is selected: compute unserved upazilas and unions within that zilla for selected year
        $unservedUpazilaIds = collect();
        $unservedUnionIds = collect();
        if ($selectedZillaId) {
            $upazilaIds = Upazila::where('zilla_id', $selectedZillaId)->pluck('id');
            $distByUpazila = $applyDateRange(ReliefApplication::where('status', 'approved')->where('zilla_id', $selectedZillaId), 'approved_at')
                ->selectRaw('upazila_id, SUM(approved_amount) as total_amount')
                ->groupBy('upazila_id')
                ->pluck('total_amount', 'upazila_id');
            $unservedUpazilaIds = $upazilaIds->filter(fn($id) => ($distByUpazila[$id] ?? 0) <= 0)->values();

            $unionIds = Union::whereIn('upazila_id', $upazilaIds)->pluck('id');
            $distByUnion = $applyDateRange(ReliefApplication::where('status', 'approved')->where('zilla_id', $selectedZillaId), 'approved_at')
                ->selectRaw('union_id, SUM(approved_amount) as total_amount')
                ->groupBy('union_id')
                ->pluck('total_amount', 'union_id');
            $unservedUnionIds = $unionIds->filter(fn($id) => ($distByUnion[$id] ?? 0) <= 0)->values();
        }

		// Duplicate allocations (same org name, same selected year)
		$duplicateAllocations = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->selectRaw('organization_name, COUNT(*) as allocations, SUM(approved_amount) as total_approved')
			->groupBy('organization_name')
			->havingRaw('COUNT(*) > 1')
			->orderByDesc('allocations')
			->get();

        // Locale-aware name maps for display (prefer Bengali when available)
        if (app()->isLocale('bn')) {
            $zillaNames = Zilla::selectRaw('id, COALESCE(name_bn, name) as disp')->pluck('disp', 'id');
            $upazilaNames = Upazila::selectRaw('id, COALESCE(name_bn, name) as disp')->pluck('disp', 'id');
            $unionNames = Union::selectRaw('id, COALESCE(name_bn, name) as disp')->pluck('disp', 'id');
        } else {
            $zillaNames = Zilla::selectRaw('id, COALESCE(name, name_bn) as disp')->pluck('disp', 'id');
            $upazilaNames = Upazila::selectRaw('id, COALESCE(name, name_bn) as disp')->pluck('disp', 'id');
            $unionNames = Union::selectRaw('id, COALESCE(name, name_bn) as disp')->pluck('disp', 'id');
        }

        // Get all upazilas for the chart (filtered by selected zilla if applicable)
        $allUpazilas = Upazila::when($selectedZillaId, function ($query) use ($selectedZillaId) {
            return $query->where('zilla_id', $selectedZillaId);
        })->select('id', 'name', 'name_bn')->orderBy('name')->get();
        $projectNames = Project::pluck('name', 'id');

        // Project units (for formatting amounts based on project relief type)
        $projectUnits = Project::with('reliefType')->get()->mapWithKeys(function ($p) {
            $unit = $p->reliefType?->unit_bn ?? $p->reliefType?->unit ?? '';
            $isMoney = in_array($unit, ['টাকা', 'Taka']);
            return [
                $p->id => [
                    'unit' => $unit,
                    'is_money' => $isMoney,
                ]
            ];
        });
        // Union -> Upazila mapping for display
        $unionUpazilaId = Union::pluck('upazila_id', 'id');

        // Area-wise summaries for dashboard (upazila and union). If zilla selected, scope to it.
        $areaBase = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at');
        if ($selectedZillaId) {
            $areaBase->where('zilla_id', $selectedZillaId);
        }
        $upazilaSummary = (clone $areaBase)
            ->selectRaw('upazila_id, SUM(approved_amount) as total_amount, COUNT(*) as application_count')
            ->groupBy('upazila_id')
            ->orderByDesc('total_amount')
            ->get();
        $unionSummary = (clone $areaBase)
            ->selectRaw('union_id, upazila_id, SUM(approved_amount) as total_amount, COUNT(*) as application_count')
            ->groupBy('union_id', 'upazila_id')
            ->orderByDesc('total_amount')
            ->get();

        // Paginate the collections
        $upazilaPaginated = $this->paginateCollection($projectUpazilaDistribution, $pageSize, $upazilaPage);
        $upazilaUnionPaginated = $this->paginateCollection($projectUpazilaUnionDistribution, $pageSize, $upazilaUnionPage);
        $upazilaSummaryPaginated = $this->paginateCollection($upazilaSummary, $pageSize, $upazilaPage);
        $unionSummaryPaginated = $this->paginateCollection($unionSummary, $pageSize, $unionSummaryPage);

		return [
			'totalReliefDistributed' => $totalReliefDistributed,
			'totalApplications' => $totalApplications,
			'pendingApplications' => $pendingApplications,
			'approvedApplications' => $approvedApplications,
			'rejectedApplications' => $rejectedApplications,
			'totalProjects' => $totalProjects,
			'activeProjects' => $activeProjects,
			'completedProjects' => $completedProjects,
			'upcomingProjects' => $upcomingProjects,
			'totalAllocatedAmount' => $totalAllocatedAmount,
			'currentYearAllocatedAmount' => $currentYearAllocatedAmount,
			'areaWiseStats' => $areaWiseStats,
			'orgTypeStats' => $orgTypeStats,
			'reliefTypeStats' => $reliefTypeStats,
			'projectAllocationStats' => $projectAllocationStats,
			'reliefTypeAllocationStats' => $reliefTypeAllocationStats,
            // Inventory statistics removed
			'projectAreaDistribution' => $projectAreaDistribution,
			'projectUpazilaDistribution' => $upazilaPaginated['data'],
			'projectUpazilaUnionDistribution' => $upazilaUnionPaginated['data'],
			'coverage' => [
				'unserved_zilla_ids' => $unservedZillaIds,
				'underserved_zilla_ids' => $underservedZillaIds,
                'unserved_upazila_ids' => $unservedUpazilaIds,
                'unserved_union_ids' => $unservedUnionIds,
			],
			'duplicateAllocations' => $duplicateAllocations,
			'zillaNames' => $zillaNames,
			'projectNames' => $projectNames,
			'upazilaNames' => $upazilaNames,
			'unionNames' => $unionNames,
			'allUpazilas' => $allUpazilas,
            'projectUnits' => $projectUnits,
            'unionUpazilaId' => $unionUpazilaId,
            'upazilaSummary' => $upazilaSummaryPaginated['data'],
            'unionSummary' => $unionSummaryPaginated['data'],
            'upazilaUnionSummary' => $unionSummaryPaginated['data'], // Alias for dashboard compatibility
            // Pagination data
            'upazilaPagination' => $upazilaPaginated['pagination'],
            'upazilaUnionPagination' => $upazilaUnionPaginated['pagination'],
            'upazilaSummaryPagination' => $upazilaSummaryPaginated['pagination'],
            'unionSummaryPagination' => $unionSummaryPaginated['pagination'],
		];
	}

	/**
	 * Get chart data for dashboard.
	 */
	private function getChartData(?EconomicYear $year = null): array
	{
		$start = $year?->start_date;
		$end = $year?->end_date;
		
        $applyDateRange = function ($query, $column = 'created_at') use ($start, $end) {
			if ($start && $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                $query->whereBetween($column, [$s, $e]);
			}
			return $query;
		};

		// Application status pie chart data
		$statusData = [
			'labels' => ['Pending', 'Approved', 'Rejected'],
			'data' => [
				$applyDateRange(ReliefApplication::where('status', 'pending'))->count(),
				$applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')->count(),
				$applyDateRange(ReliefApplication::where('status', 'rejected'))->count(),
			],
			'colors' => ['#f59e0b', '#10b981', '#ef4444']
		];

		// Area-wise relief distribution
		$areaData = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->join('zillas', 'relief_applications.zilla_id', '=', 'zillas.id')
			->select('zillas.name as zilla_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'))
			->groupBy('zillas.id', 'zillas.name')
			->orderBy('total_amount', 'desc')
			->limit(10)
			->get();

		// Organization type distribution
		$orgTypeData = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->leftJoin('organization_types', 'relief_applications.organization_type_id', '=', 'organization_types.id')
			->select('organization_types.name as org_type_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'))
			->groupBy('organization_types.id', 'organization_types.name')
			->orderBy('total_amount', 'desc')
			->get();

		// Relief type distribution
		$reliefTypeData = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->join('relief_types', 'relief_applications.relief_type_id', '=', 'relief_types.id')
			->select(
				DB::raw(app()->isLocale('bn') ? 'COALESCE(relief_types.name_bn, relief_types.name) as relief_type_name' : 'COALESCE(relief_types.name, relief_types.name_bn) as relief_type_name'),
				'relief_types.color_code', 
				DB::raw('SUM(relief_applications.approved_amount) as total_amount')
			)
			->groupBy('relief_types.id', 'relief_types.name', 'relief_types.name_bn', 'relief_types.color_code')
			->orderBy('total_amount', 'desc')
			->get();

		// Monthly relief distribution (within selected year if provided, else last 12 months)
		$monthlyQuery = ReliefApplication::where('status', 'approved');
		$applyDateRange($monthlyQuery, 'approved_at');
		if (!($start && $end)) {
			$monthlyQuery->where('approved_at', '>=', now()->subMonths(12));
		}
		$monthlyData = $monthlyQuery
			->select(DB::raw('DATE_FORMAT(approved_at, "%Y-%m") as month'), DB::raw('SUM(approved_amount) as total_amount'))
			->groupBy('month')
			->orderBy('month')
			->get();

		// Applications intake and approvals by month
		$appsByMonth = $applyDateRange(ReliefApplication::query())
			->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"), DB::raw('COUNT(*) as c'))
			->groupBy('ym')
			->orderBy('ym')
			->get();
		$approvalsByMonth = $applyDateRange(ReliefApplication::where('status', 'approved'), 'approved_at')
			->select(DB::raw("DATE_FORMAT(approved_at, '%Y-%m') as ym"), DB::raw('COUNT(*) as c'))
			->groupBy('ym')
			->orderBy('ym')
			->get();

		return [
			'statusData' => $statusData,
			'areaData' => $areaData,
			'orgTypeData' => $orgTypeData,
			'reliefTypeData' => $reliefTypeData,
			'monthlyData' => $monthlyData,
			'applicationsByMonth' => $appsByMonth,
			'approvalsByMonth' => $approvalsByMonth,
		];
	}

	/**
	 * Get dashboard data as JSON for AJAX requests.
	 */
	public function getDashboardData(Request $request): JsonResponse
	{
		$years = EconomicYear::orderByDesc('start_date')->get();
		$selectedYear = $this->resolveSelectedYear($request, $years);
		$selectedZillaId = $request->integer('zilla_id');
		$sort = $request->get('sort');

		// Default zilla when only one exists
		if (!$selectedZillaId) {
			$onlyZilla = Zilla::query()->select('id')->limit(2)->get();
			if ($onlyZilla->count() === 1) {
				$selectedZillaId = $onlyZilla->first()->id;
			}
		}
        
        // Pagination parameters
        $pageSize = 15;
        $upazilaPage = $request->integer('upazila_page', 1);
        $upazilaUnionPage = $request->integer('upazila_union_page', 1);
        $unionSummaryPage = $request->integer('union_summary_page', 1);
		
		$stats = $this->getDashboardStatistics($selectedYear, $selectedZillaId, $sort, $pageSize, $upazilaPage, $upazilaUnionPage, $unionSummaryPage);
		$chartData = $this->getChartData($selectedYear);
		
		return response()->json([
			'stats' => $stats,
			'chartData' => $chartData,
			'filters' => [
				'economic_year_id' => $selectedYear?->id,
				'zilla_id' => $selectedZillaId,
			],
		]);
	}

	/**
	 * Resolve selected economic year from request or default to current/most recent.
	 */
	private function resolveSelectedYear(Request $request, $years): ?EconomicYear
	{
		$yearId = $request->integer('economic_year_id');
		if ($yearId) {
			return $years->firstWhere('id', $yearId) ?? EconomicYear::find($yearId);
		}
		
		// Use the auto-detection method to ensure we get the correct current year
		return EconomicYear::getCurrent() ?? $years->first();
	}

	/**
	 * Paginate a collection manually.
	 */
	private function paginateCollection($collection, int $pageSize, int $currentPage): array
	{
		$totalItems = $collection->count();
		$totalPages = ceil($totalItems / $pageSize);
		$currentPage = max(1, min($currentPage, $totalPages));
		$offset = ($currentPage - 1) * $pageSize;
		
		$paginatedData = $collection->slice($offset, $pageSize)->values();
		
		return [
			'data' => $paginatedData,
			'pagination' => [
				'current_page' => $currentPage,
				'total_pages' => $totalPages,
				'total_items' => $totalItems,
				'page_size' => $pageSize,
				'has_previous' => $currentPage > 1,
				'has_next' => $currentPage < $totalPages,
				'previous_page' => $currentPage > 1 ? $currentPage - 1 : null,
				'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null,
			]
		];
	}
}