<?php

namespace App\Http\Controllers;

use App\Models\ReliefApplication;
use App\Models\ReliefApplicationItem;
use App\Models\Project;
use App\Models\Inventory;
use App\Models\ReliefItem;
use App\Models\Zilla;
use App\Models\OrganizationType;
use App\Models\ReliefType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	/**
	 * Display the dashboard.
	 */
	public function index(): View
	{
		// Get basic statistics
		$stats = $this->getDashboardStatistics();
		
		// Get chart data
		$chartData = $this->getChartData();
		
		return view('dashboard', compact('stats', 'chartData'));
	}

	/**
	 * Get dashboard statistics.
	 */
	private function getDashboardStatistics(): array
	{
		// Total relief distributed
		$totalReliefDistributed = ReliefApplication::where('status', 'approved')->sum('approved_amount');
		
		// Total applications
		$totalApplications = ReliefApplication::count();
		$pendingApplications = ReliefApplication::where('status', 'pending')->count();
		$approvedApplications = ReliefApplication::where('status', 'approved')->count();
		$rejectedApplications = ReliefApplication::where('status', 'rejected')->count();
		
		// Project statistics
		$totalProjects = Project::count();
		$activeProjects = Project::where('start_date', '<=', now())
			->where('end_date', '>=', now())
			->count();
		$totalProjectBudget = Project::sum('budget');
		$remainingProjectBudget = Project::where('start_date', '<=', now())
			->where('end_date', '>=', now())
			->sum('budget');
		
		// Area-wise statistics
		$areaWiseStats = ReliefApplication::where('status', 'approved')
			->join('zillas', 'relief_applications.zilla_id', '=', 'zillas.id')
			->select('zillas.name as zilla_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'), DB::raw('COUNT(*) as application_count'))
			->groupBy('zillas.id', 'zillas.name')
			->orderBy('total_amount', 'desc')
			->get();
		
		// Organization type statistics
		$orgTypeStats = ReliefApplication::where('status', 'approved')
			->leftJoin('organization_types', 'relief_applications.organization_type_id', '=', 'organization_types.id')
			->select('organization_types.name as org_type_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'), DB::raw('COUNT(*) as application_count'))
			->groupBy('organization_types.id', 'organization_types.name')
			->orderBy('total_amount', 'desc')
			->get();
		
		// Relief type statistics
		$reliefTypeStats = ReliefApplication::where('status', 'approved')
			->join('relief_types', 'relief_applications.relief_type_id', '=', 'relief_types.id')
			->select('relief_types.name as relief_type_name', 'relief_types.color_code', DB::raw('SUM(relief_applications.approved_amount) as total_amount'), DB::raw('COUNT(*) as application_count'))
			->groupBy('relief_types.id', 'relief_types.name', 'relief_types.color_code')
			->orderBy('total_amount', 'desc')
			->get();
		
		// Project budget remaining
		$projectBudgetStats = Project::where('start_date', '<=', now())
			->where('end_date', '>=', now())
			->select('name', 'budget', 'relief_type_id')
			->with('reliefType')
			->orderBy('budget', 'desc')
			->get();

		// Inventory statistics
		$totalInventoryValue = Inventory::sum(DB::raw('current_stock * unit_price'));
		$totalInventoryItems = Inventory::count();
		$lowStockItems = Inventory::whereRaw('current_stock < (total_received * 0.2)')->count(); // Less than 20% of total received
		$totalDistributedItems = Inventory::sum('total_distributed');
		$totalReservedItems = Inventory::sum('reserved_stock');

		// Relief item distribution statistics
		$reliefItemStats = ReliefApplicationItem::whereHas('reliefApplication', function($query) {
				$query->where('status', 'approved');
			})
			->join('relief_items', 'relief_application_items.relief_item_id', '=', 'relief_items.id')
			->select('relief_items.name as item_name', 'relief_items.type as item_type', 'relief_items.unit as item_unit', 
				DB::raw('SUM(relief_application_items.quantity_requested) as total_quantity_requested'),
				DB::raw('SUM(relief_application_items.quantity_approved) as total_quantity_approved'),
				DB::raw('SUM(relief_application_items.total_amount) as total_amount'))
			->groupBy('relief_items.id', 'relief_items.name', 'relief_items.type', 'relief_items.unit')
			->orderBy('total_quantity_approved', 'desc')
			->get();

		return [
			'totalReliefDistributed' => $totalReliefDistributed,
			'totalApplications' => $totalApplications,
			'pendingApplications' => $pendingApplications,
			'approvedApplications' => $approvedApplications,
			'rejectedApplications' => $rejectedApplications,
			'totalProjects' => $totalProjects,
			'activeProjects' => $activeProjects,
			'totalProjectBudget' => $totalProjectBudget,
			'remainingProjectBudget' => $remainingProjectBudget,
			'areaWiseStats' => $areaWiseStats,
			'orgTypeStats' => $orgTypeStats,
			'reliefTypeStats' => $reliefTypeStats,
			'projectBudgetStats' => $projectBudgetStats,
			// Inventory statistics
			'totalInventoryValue' => $totalInventoryValue,
			'totalInventoryItems' => $totalInventoryItems,
			'lowStockItems' => $lowStockItems,
			'totalDistributedItems' => $totalDistributedItems,
			'totalReservedItems' => $totalReservedItems,
			'reliefItemStats' => $reliefItemStats,
		];
	}

	/**
	 * Get chart data for dashboard.
	 */
	private function getChartData(): array
	{
		// Application status pie chart data
		$statusData = [
			'labels' => ['Pending', 'Approved', 'Rejected'],
			'data' => [
				ReliefApplication::where('status', 'pending')->count(),
				ReliefApplication::where('status', 'approved')->count(),
				ReliefApplication::where('status', 'rejected')->count(),
			],
			'colors' => ['#f59e0b', '#10b981', '#ef4444']
		];

		// Area-wise relief distribution
		$areaData = ReliefApplication::where('status', 'approved')
			->join('zillas', 'relief_applications.zilla_id', '=', 'zillas.id')
			->select('zillas.name as zilla_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'))
			->groupBy('zillas.id', 'zillas.name')
			->orderBy('total_amount', 'desc')
			->limit(10)
			->get();

		// Organization type distribution
		$orgTypeData = ReliefApplication::where('status', 'approved')
			->leftJoin('organization_types', 'relief_applications.organization_type_id', '=', 'organization_types.id')
			->select('organization_types.name as org_type_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'))
			->groupBy('organization_types.id', 'organization_types.name')
			->orderBy('total_amount', 'desc')
			->get();

		// Relief type distribution
		$reliefTypeData = ReliefApplication::where('status', 'approved')
			->join('relief_types', 'relief_applications.relief_type_id', '=', 'relief_types.id')
			->select('relief_types.name as relief_type_name', 'relief_types.color_code', DB::raw('SUM(relief_applications.approved_amount) as total_amount'))
			->groupBy('relief_types.id', 'relief_types.name', 'relief_types.color_code')
			->orderBy('total_amount', 'desc')
			->get();

		// Monthly relief distribution (last 12 months)
		$monthlyData = ReliefApplication::where('status', 'approved')
			->where('approved_at', '>=', now()->subMonths(12))
			->select(DB::raw('DATE_FORMAT(approved_at, "%Y-%m") as month'), DB::raw('SUM(approved_amount) as total_amount'))
			->groupBy('month')
			->orderBy('month')
			->get();

		return [
			'statusData' => $statusData,
			'areaData' => $areaData,
			'orgTypeData' => $orgTypeData,
			'reliefTypeData' => $reliefTypeData,
			'monthlyData' => $monthlyData,
		];
	}

	/**
	 * Get dashboard data as JSON for AJAX requests.
	 */
	public function getDashboardData(): JsonResponse
	{
		$stats = $this->getDashboardStatistics();
		$chartData = $this->getChartData();
		
		return response()->json([
			'stats' => $stats,
			'chartData' => $chartData,
		]);
	}
}