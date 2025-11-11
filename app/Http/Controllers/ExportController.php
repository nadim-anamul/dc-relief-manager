<?php

namespace App\Http\Controllers;

use App\Exports\ReliefApplicationExport;
use App\Exports\ProjectSummaryExport;
use App\Exports\AreaWiseReliefExport;
use App\Models\ReliefApplication;
use App\Models\Project;
use App\Models\Zilla;
use App\Models\ReliefType;
use App\Models\OrganizationType;
use App\Models\EconomicYear;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;

class ExportController extends Controller
{
	/**
	 * Export relief applications to Excel.
	 */
	public function exportReliefApplicationsExcel(Request $request)
	{
		$filters = $request->only([
			'status', 'relief_type_id', 'organization_type_id', 
			'zilla_id', 'start_date', 'end_date'
		]);

		$fileName = 'relief_applications_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

		return Excel::download(new ReliefApplicationExport($filters), $fileName);
	}

	/**
	 * Export relief applications to PDF.
	 */
	public function exportReliefApplicationsPdf(Request $request)
	{
		$filters = $request->only([
			'status', 'relief_type_id', 'organization_type_id', 
			'zilla_id', 'start_date', 'end_date', 'economic_year_id', 'project_id'
		]);

		$query = ReliefApplication::with([
			'organizationType', 'zilla', 'upazila', 'union', 'ward', 
			'reliefType', 'project', 'approvedBy', 'rejectedBy',
			'createdBy', 'updatedBy', 'project.economicYear'
		]);

		// Default zilla filter (set to Bogura zilla ID 1) - same as admin controller
		$defaultZillaId = 1;
		if (!$request->filled('zilla_id')) {
			$query->where('zilla_id', $defaultZillaId);
		}

		// Apply filters - same as ReliefApplicationController
		if (isset($filters['status']) && $filters['status'] !== 'all') {
			$query->where('status', $filters['status']);
		}

		if (isset($filters['relief_type_id'])) {
			$query->where('relief_type_id', $filters['relief_type_id']);
		}

		if (isset($filters['organization_type_id'])) {
			$query->where('organization_type_id', $filters['organization_type_id']);
		}

		if (isset($filters['zilla_id'])) {
			$query->where('zilla_id', $filters['zilla_id']);
		}

		// Filter by economic year if provided (but not if project_id is also provided, as project determines economic year)
		if (isset($filters['economic_year_id']) && !isset($filters['project_id'])) {
			$query->whereHas('project', function($q) use ($filters) {
				$q->where('economic_year_id', $filters['economic_year_id']);
			});
		} elseif (!isset($filters['project_id'])) {
			// Default to current economic year if no specific year is selected
			$currentYear = \App\Models\EconomicYear::where('is_current', true)->first();
			if ($currentYear) {
				$query->whereHas('project', function($q) use ($currentYear) {
					$q->where('economic_year_id', $currentYear->id);
				});
			}
		}

		// Filter by project if provided
		if (isset($filters['project_id'])) {
			$query->where('project_id', $filters['project_id']);
		}

		if (isset($filters['start_date'])) {
			$query->where('date', '>=', $filters['start_date']);
		}

		if (isset($filters['end_date'])) {
			$query->where('date', '<=', $filters['end_date']);
		}

		$reliefApplications = $query->orderBy('created_at', 'desc')->get();

		// Calculate summary statistics - same as admin controller
		$totalApplications = $reliefApplications->count();
		$pendingApplications = $reliefApplications->where('status', 'pending')->count();
		$approvedApplications = $reliefApplications->where('status', 'approved')->count();
		$rejectedApplications = $reliefApplications->where('status', 'rejected')->count();
		$totalApprovedAmount = $reliefApplications->where('status', 'approved')->sum('approved_amount');
		$totalRequestedAmount = $reliefApplications->sum('amount_requested');

		$html = view('exports.relief-applications-pdf', [
			'reliefApplications' => $reliefApplications,
			'filters' => $filters,
			'exportDate' => now(),
			'totalApplications' => $totalApplications,
			'pendingApplications' => $pendingApplications,
			'approvedApplications' => $approvedApplications,
			'rejectedApplications' => $rejectedApplications,
			'totalApprovedAmount' => $totalApprovedAmount,
			'totalRequestedAmount' => $totalRequestedAmount,
		])->render();

		$fileName = 'relief_applications_' . now()->format('Y-m-d_H-i-s') . '.pdf';
		$tempPath = storage_path('app/temp/' . $fileName);

		if (!file_exists(dirname($tempPath))) {
			mkdir(dirname($tempPath), 0755, true);
		}

		Browsershot::html($html)
			->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
			->format('A4')
			->margins(15, 15, 15, 15)
			->showBackground()
			->waitUntilNetworkIdle()
			->save($tempPath);

		return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
	}

	/**
	 * Export project summary to Excel.
	 */
	public function exportProjectSummaryExcel(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'relief_type_id', 'status', 'start_date', 'end_date'
		]);

		$fileName = 'project_summary_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

		return Excel::download(new ProjectSummaryExport($filters), $fileName);
	}

	/**
	 * Export project summary to PDF.
	 */
	public function exportProjectSummaryPdf(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'relief_type_id', 'status', 'start_date', 'end_date'
		]);

		$query = Project::with([
			'economicYear', 'reliefType', 'createdBy', 'updatedBy'
		]);

		// Apply filters - same as ProjectController
		if (isset($filters['economic_year_id'])) {
			$query->where('economic_year_id', $filters['economic_year_id']);
		} else {
			$currentYear = EconomicYear::where('is_current', true)->first();
			if ($currentYear) {
				$query->where('economic_year_id', $currentYear->id);
			}
		}

		if (isset($filters['relief_type_id'])) {
			$query->where('relief_type_id', $filters['relief_type_id']);
		}

		if (isset($filters['status'])) {
			switch ($filters['status']) {
				case 'active':
					$query->active();
					break;
				case 'completed':
					$query->completed();
					break;
				case 'upcoming':
					$query->upcoming();
					break;
			}
		}

		if (isset($filters['start_date'])) {
			$query->whereHas('economicYear', function($q) use ($filters) {
				$q->where('start_date', '>=', $filters['start_date']);
			});
		}

		if (isset($filters['end_date'])) {
			$query->whereHas('economicYear', function($q) use ($filters) {
				$q->where('end_date', '<=', $filters['end_date']);
			});
		}

		$projects = $query->orderBy('created_at', 'desc')->get();

		// Calculate summary statistics - same as ProjectController
		$totalProjects = $projects->count();
		$totalBudget = $projects->sum('allocated_amount');
		$activeProjects = $projects->filter(function($project) {
			return $project->status === 'Active';
		})->count();
		$completedProjects = $projects->filter(function($project) {
			return $project->status === 'Completed';
		})->count();

		// Calculate relief type allocation statistics with status filter
		$reliefTypeStats = Project::query()
			->when(isset($filters['economic_year_id']) && $filters['economic_year_id'], function($q) use ($filters) {
				$q->where('economic_year_id', $filters['economic_year_id']);
			}, function($q) {
				$currentYear = EconomicYear::where('is_current', true)->first();
				if ($currentYear) {
					$q->where('economic_year_id', $currentYear->id);
				}
			})
			->when(isset($filters['relief_type_id']) && $filters['relief_type_id'], function($q) use ($filters) {
				$q->where('relief_type_id', $filters['relief_type_id']);
			})
			->when(isset($filters['status']) && $filters['status'], function($q) use ($filters) {
				switch ($filters['status']) {
					case 'active':
						$q->active();
						break;
					case 'completed':
						$q->completed();
						break;
					case 'upcoming':
						$q->upcoming();
						break;
				}
			})
			->selectRaw('relief_type_id, SUM(allocated_amount) as total_allocated, COUNT(*) as project_count')
			->with('reliefType')
			->groupBy('relief_type_id')
			->orderBy('total_allocated', 'desc')
			->get()
			->map(function($stat) {
				$unit = $stat->reliefType?->unit_bn ?? $stat->reliefType?->unit ?? '';
				$amount = number_format((float)$stat->total_allocated, 2);
				
				if (in_array($unit, ['টাকা', 'Taka'])) {
					$stat->formatted_total = '৳' . $amount;
				} else {
					$stat->formatted_total = $amount . ' ' . $unit;
				}
				
				return $stat;
			});

		$html = view('exports.project-summary-pdf', [
			'projects' => $projects,
			'filters' => $filters,
			'exportDate' => now(),
			'totalProjects' => $totalProjects,
			'totalBudget' => $totalBudget,
			'activeProjects' => $activeProjects,
			'completedProjects' => $completedProjects,
			'reliefTypeStats' => $reliefTypeStats,
		])->render();

		$fileName = 'project_summary_' . now()->format('Y-m-d_H-i-s') . '.pdf';
		$tempPath = storage_path('app/temp/' . $fileName);

		if (!file_exists(dirname($tempPath))) {
			mkdir(dirname($tempPath), 0755, true);
		}

		Browsershot::html($html)
			->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
			->format('A4')
			->margins(15, 15, 15, 15)
			->showBackground()
			->waitUntilNetworkIdle()
			->save($tempPath);

		return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
	}

	/**
	 * Export area-wise relief distribution to Excel.
	 */
	public function exportAreaWiseReliefExcel(Request $request)
	{
		$filters = $request->only([
			'zilla_id', 'relief_type_id', 'organization_type_id', 
			'start_date', 'end_date'
		]);

		$fileName = 'area_wise_relief_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

		return Excel::download(new AreaWiseReliefExport($filters), $fileName);
	}

	/**
	 * Export area-wise relief distribution to PDF.
	 */
	public function exportAreaWiseReliefPdf(Request $request)
	{
		$filters = $request->only([
			'zilla_id', 'relief_type_id', 'organization_type_id', 
			'start_date', 'end_date'
		]);

		$query = ReliefApplication::where('status', 'approved')
			->join('zillas', 'relief_applications.zilla_id', '=', 'zillas.id')
			->join('upazilas', 'relief_applications.upazila_id', '=', 'upazilas.id')
			->join('unions', 'relief_applications.union_id', '=', 'unions.id')
			->join('wards', 'relief_applications.ward_id', '=', 'wards.id')
			->join('relief_types', 'relief_applications.relief_type_id', '=', 'relief_types.id')
			->leftJoin('organization_types', 'relief_applications.organization_type_id', '=', 'organization_types.id')
			->select(
				'zillas.name as zilla_name',
				'upazilas.name as upazila_name',
				'unions.name as union_name',
				'wards.name as ward_name',
				'relief_types.name as relief_type_name',
				'organization_types.name as org_type_name',
				\DB::raw('SUM(relief_applications.approved_amount) as total_amount'),
				\DB::raw('COUNT(*) as application_count'),
				\DB::raw('AVG(relief_applications.approved_amount) as avg_amount'),
				\DB::raw('MIN(relief_applications.approved_amount) as min_amount'),
				\DB::raw('MAX(relief_applications.approved_amount) as max_amount')
			)
			->groupBy(
				'zillas.id', 'zillas.name',
				'upazilas.id', 'upazilas.name',
				'unions.id', 'unions.name',
				'wards.id', 'wards.name',
				'relief_types.id', 'relief_types.name',
				'organization_types.id', 'organization_types.name'
			)
			->orderBy('total_amount', 'desc');

		// Apply filters
		if (isset($filters['zilla_id'])) {
			$query->where('zillas.id', $filters['zilla_id']);
		}

		if (isset($filters['relief_type_id'])) {
			$query->where('relief_types.id', $filters['relief_type_id']);
		}

		if (isset($filters['organization_type_id'])) {
			$query->where('organization_types.id', $filters['organization_type_id']);
		}

		if (isset($filters['start_date'])) {
			$query->where('relief_applications.approved_at', '>=', $filters['start_date']);
		}

		if (isset($filters['end_date'])) {
			$query->where('relief_applications.approved_at', '<=', $filters['end_date']);
		}

		$areaWiseData = $query->get();

		// Calculate summary statistics
		$totalAmount = $areaWiseData->sum('total_amount');
		$totalApplications = $areaWiseData->sum('application_count');
		$uniqueAreas = $areaWiseData->count();

		$pdf = Pdf::loadView('exports.area-wise-relief-pdf', [
			'areaWiseData' => $areaWiseData,
			'filters' => $filters,
			'exportDate' => now(),
			'totalAmount' => $totalAmount,
			'totalApplications' => $totalApplications,
			'uniqueAreas' => $uniqueAreas,
		])
		->setPaper('a4', 'portrait')
		->setOptions([
			'isHtml5ParserEnabled' => true,
			'isRemoteEnabled' => false,
			'isPhpEnabled' => false,
			'fontDir' => storage_path('fonts/'),
			'fontCache' => storage_path('fonts/'),
		]);

		$fileName = 'area_wise_relief_' . now()->format('Y-m-d_H-i-s') . '.pdf';

		return $pdf->download($fileName);
	}

	/**
	 * Export consolidated distribution to Excel.
	 */
	public function exportConsolidatedDistributionExcel(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'zilla_id', 'upazila_id', 'union_id', 'project_id'
		]);

		$fileName = 'consolidated_distribution_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

		return Excel::download(new ConsolidatedDistributionExport($filters), $fileName);
	}

	/**
	 * Export consolidated distribution to PDF.
	 */
	public function exportConsolidatedDistributionPdf(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'zilla_id', 'upazila_id', 'union_id', 'project_id'
		]);

		// Get the same data as the controller
		$selectedYear = isset($filters['economic_year_id']) 
			? \App\Models\EconomicYear::find($filters['economic_year_id'])
			: \App\Models\EconomicYear::where('is_current', true)->first();

		$selectedZillaId = $filters['zilla_id'] ?? null;
		$selectedUpazilaId = $filters['upazila_id'] ?? null;
		$selectedUnionId = $filters['union_id'] ?? null;
		$selectedProjectId = $filters['project_id'] ?? null;

		// Get data using the same logic as DistributionController
		$data = $this->getConsolidatedDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedUnionId, $selectedProjectId);
		$projectBudgetBreakdown = $this->getProjectBudgetBreakdown($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId);

		// Get project units (for formatting amounts based on project relief type)
		$projectUnits = \App\Models\Project::with('reliefType')->get()->mapWithKeys(function ($p) {
			$unit = $p->reliefType?->unit_bn ?? $p->reliefType?->unit ?? '';
			$isMoney = in_array($unit, ['টাকা', 'Taka']);
			return [
				$p->id => [
					'unit' => $unit,
					'is_money' => $isMoney,
				]
			];
		});

		$html = view('exports.consolidated-distribution-pdf', [
			'data' => $data,
			'projectBudgetBreakdown' => $projectBudgetBreakdown,
			'projectUnits' => $projectUnits,
			'filters' => $filters,
			'exportDate' => now(),
			'selectedYear' => $selectedYear,
			'selectedZillaId' => $selectedZillaId,
			'selectedUpazilaId' => $selectedUpazilaId,
			'selectedUnionId' => $selectedUnionId,
			'selectedProjectId' => $selectedProjectId,
		])->render();

		$fileName = 'consolidated_distribution_' . now()->format('Y-m-d_H-i-s') . '.pdf';
		$tempPath = storage_path('app/temp/' . $fileName);

		if (!file_exists(dirname($tempPath))) {
			mkdir(dirname($tempPath), 0755, true);
		}

		Browsershot::html($html)
			->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
			->format('A4')
			->margins(15, 15, 15, 15)
			->showBackground()
			->waitUntilNetworkIdle()
			->save($tempPath);

		return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
	}

	/**
	 * Export detailed upazila distribution to Excel.
	 */
	public function exportDetailedUpazilaDistributionExcel(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'zilla_id', 'upazila_id', 'search'
		]);

		$fileName = 'detailed_upazila_distribution_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

		return Excel::download(new DetailedUpazilaDistributionExport($filters), $fileName);
	}

	/**
	 * Export detailed upazila distribution to PDF.
	 */
	public function exportDetailedUpazilaDistributionPdf(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'zilla_id', 'upazila_id', 'search'
		]);

		// Get the same data as the controller
		$selectedYear = isset($filters['economic_year_id']) 
			? \App\Models\EconomicYear::find($filters['economic_year_id'])
			: \App\Models\EconomicYear::where('is_current', true)->first();

		$selectedZillaId = $filters['zilla_id'] ?? null;
		$selectedUpazilaId = $filters['upazila_id'] ?? null;
		$search = $filters['search'] ?? '';

		$data = $this->getUpazilaDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $search, 1000, 1);

		// Get project units (for formatting amounts based on project relief type)
		$projectUnits = \App\Models\Project::with('reliefType')->get()->mapWithKeys(function ($p) {
			$unit = $p->reliefType?->unit_bn ?? $p->reliefType?->unit ?? '';
			$isMoney = in_array($unit, ['টাকা', 'Taka']);
			return [
				$p->id => [
					'unit' => $unit,
					'is_money' => $isMoney,
				]
			];
		});

		$html = view('exports.detailed-upazila-distribution-pdf', [
			'data' => $data,
			'projectUnits' => $projectUnits,
			'filters' => $filters,
			'exportDate' => now(),
			'selectedYear' => $selectedYear,
			'selectedZillaId' => $selectedZillaId,
			'selectedUpazilaId' => $selectedUpazilaId,
			'search' => $search,
		])->render();

		$fileName = 'detailed_upazila_distribution_' . now()->format('Y-m-d_H-i-s') . '.pdf';
		$tempPath = storage_path('app/temp/' . $fileName);

		if (!file_exists(dirname($tempPath))) {
			mkdir(dirname($tempPath), 0755, true);
		}

		Browsershot::html($html)
			->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
			->format('A4')
			->margins(15, 15, 15, 15)
			->showBackground()
			->waitUntilNetworkIdle()
			->save($tempPath);

		return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
	}

	/**
	 * Export detailed union distribution to Excel.
	 */
	public function exportDetailedUnionDistributionExcel(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'zilla_id', 'upazila_id', 'union_id', 'search'
		]);

		$fileName = 'detailed_union_distribution_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

		return Excel::download(new DetailedUnionDistributionExport($filters), $fileName);
	}

	/**
	 * Export detailed union distribution to PDF.
	 */
	public function exportDetailedUnionDistributionPdf(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'zilla_id', 'upazila_id', 'union_id', 'search'
		]);

		// Get the same data as the controller
		$selectedYear = isset($filters['economic_year_id']) 
			? \App\Models\EconomicYear::find($filters['economic_year_id'])
			: \App\Models\EconomicYear::where('is_current', true)->first();

		$selectedZillaId = $filters['zilla_id'] ?? null;
		$selectedUpazilaId = $filters['upazila_id'] ?? null;
		$selectedUnionId = $filters['union_id'] ?? null;
		$search = $filters['search'] ?? '';

		$data = $this->getUnionDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedUnionId, $search, 1000, 1);

		// Get project units (for formatting amounts based on project relief type)
		$projectUnits = \App\Models\Project::with('reliefType')->get()->mapWithKeys(function ($p) {
			$unit = $p->reliefType?->unit_bn ?? $p->reliefType?->unit ?? '';
			$isMoney = in_array($unit, ['টাকা', 'Taka']);
			return [
				$p->id => [
					'unit' => $unit,
					'is_money' => $isMoney,
				]
			];
		});

		$html = view('exports.detailed-union-distribution-pdf', [
			'data' => $data,
			'projectUnits' => $projectUnits,
			'filters' => $filters,
			'exportDate' => now(),
			'selectedYear' => $selectedYear,
			'selectedZillaId' => $selectedZillaId,
			'selectedUpazilaId' => $selectedUpazilaId,
			'selectedUnionId' => $selectedUnionId,
			'search' => $search,
		])->render();

		$fileName = 'detailed_union_distribution_' . now()->format('Y-m-d_H-i-s') . '.pdf';
		$tempPath = storage_path('app/temp/' . $fileName);

		if (!file_exists(dirname($tempPath))) {
			mkdir(dirname($tempPath), 0755, true);
		}

		Browsershot::html($html)
			->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
			->format('A4')
			->margins(15, 15, 15, 15)
			->showBackground()
			->waitUntilNetworkIdle()
			->save($tempPath);

		return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
	}

	/**
	 * Export detailed duplicates distribution to Excel.
	 */
	public function exportDetailedDuplicatesDistributionExcel(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'zilla_id', 'upazila_id', 'search'
		]);

		$fileName = 'detailed_duplicates_distribution_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

		return Excel::download(new DetailedDuplicatesDistributionExport($filters), $fileName);
	}

	/**
	 * Export detailed duplicates distribution to PDF.
	 */
	public function exportDetailedDuplicatesDistributionPdf(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'zilla_id', 'upazila_id', 'search'
		]);

		// Get the same data as the controller
		$selectedYear = isset($filters['economic_year_id']) 
			? \App\Models\EconomicYear::find($filters['economic_year_id'])
			: \App\Models\EconomicYear::where('is_current', true)->first();

		$selectedZillaId = $filters['zilla_id'] ?? null;
		$selectedUpazilaId = $filters['upazila_id'] ?? null;
		$search = $filters['search'] ?? '';

		$data = $this->getDuplicatesDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $search, 1000, 1);

		// Get project units (for formatting amounts based on project relief type)
		$projectUnits = \App\Models\Project::with('reliefType')->get()->mapWithKeys(function ($p) {
			$unit = $p->reliefType?->unit_bn ?? $p->reliefType?->unit ?? '';
			$isMoney = in_array($unit, ['টাকা', 'Taka']);
			return [
				$p->id => [
					'unit' => $unit,
					'is_money' => $isMoney,
				]
			];
		});

		$html = view('exports.detailed-duplicates-distribution-pdf', [
			'data' => $data,
			'projectUnits' => $projectUnits,
			'filters' => $filters,
			'exportDate' => now(),
			'selectedYear' => $selectedYear,
			'selectedZillaId' => $selectedZillaId,
			'selectedUpazilaId' => $selectedUpazilaId,
			'search' => $search,
		])->render();

		$fileName = 'detailed_duplicates_distribution_' . now()->format('Y-m-d_H-i-s') . '.pdf';
		$tempPath = storage_path('app/temp/' . $fileName);

		if (!file_exists(dirname($tempPath))) {
			mkdir(dirname($tempPath), 0755, true);
		}

		Browsershot::html($html)
			->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
			->format('A4')
			->margins(15, 15, 15, 15)
			->showBackground()
			->waitUntilNetworkIdle()
			->save($tempPath);

		return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
	}

	/**
	 * Export detailed projects distribution to Excel.
	 */
	public function exportDetailedProjectsDistributionExcel(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'search'
		]);

		$fileName = 'detailed_projects_distribution_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

		return Excel::download(new DetailedProjectsDistributionExport($filters), $fileName);
	}

	/**
	 * Export detailed projects distribution to PDF.
	 */
	public function exportDetailedProjectsDistributionPdf(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'search'
		]);

		// Get the same data as the controller
		$selectedYear = isset($filters['economic_year_id']) 
			? \App\Models\EconomicYear::find($filters['economic_year_id'])
			: \App\Models\EconomicYear::where('is_current', true)->first();

		$search = $filters['search'] ?? '';

		$data = $this->getProjectAllocationsData($selectedYear, $search, 1000, 1);

		// Get project units (for formatting amounts based on project relief type)
		$projectUnits = \App\Models\Project::with('reliefType')->get()->mapWithKeys(function ($p) {
			$unit = $p->reliefType?->unit_bn ?? $p->reliefType?->unit ?? '';
			$isMoney = in_array($unit, ['টাকা', 'Taka']);
			return [
				$p->id => [
					'unit' => $unit,
					'is_money' => $isMoney,
				]
			];
		});

		$html = view('exports.detailed-projects-distribution-pdf', [
			'data' => $data,
			'projectUnits' => $projectUnits,
			'filters' => $filters,
			'exportDate' => now(),
			'selectedYear' => $selectedYear,
			'search' => $search,
		])->render();

		$fileName = 'detailed_projects_distribution_' . now()->format('Y-m-d_H-i-s') . '.pdf';
		$tempPath = storage_path('app/temp/' . $fileName);

		if (!file_exists(dirname($tempPath))) {
			mkdir(dirname($tempPath), 0755, true);
		}

		Browsershot::html($html)
			->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
			->format('A4')
			->margins(15, 15, 15, 15)
			->showBackground()
			->waitUntilNetworkIdle()
			->save($tempPath);

		return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
	}

	/**
	 * Get consolidated distribution data (same logic as DistributionController).
	 */
	private function getConsolidatedDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedUnionId, $selectedProjectId)
	{
		$start = $selectedYear?->start_date;
		$end = $selectedYear?->end_date;

		$query = \App\Models\ReliefApplication::where('status', 'approved')
			->with(['project.reliefType', 'zilla', 'upazila', 'union', 'organizationType'])
			->when($start && $end, function ($q) use ($start, $end) {
				$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
				$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
				return $q->whereBetween('approved_at', [$s, $e]);
			})
			->when($selectedZillaId, fn($q) => $q->where('zilla_id', $selectedZillaId))
			->when($selectedUpazilaId, fn($q) => $q->where('upazila_id', $selectedUpazilaId))
			->when($selectedUnionId, fn($q) => $q->where('union_id', $selectedUnionId))
			->when($selectedProjectId, fn($q) => $q->where('project_id', $selectedProjectId));

		$distribution = $query->orderByDesc('approved_amount')->get();
		$totalItems = $distribution->count();

		// Calculate totals by unit
		$totalsByUnitQuery = \Illuminate\Support\Facades\DB::table('relief_applications as ra')
			->leftJoin('projects as p', 'ra.project_id', '=', 'p.id')
			->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
			->where('ra.status', 'approved')
			->when($start && $end, function ($q) use ($start, $end) {
				$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
				$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
				return $q->whereBetween('ra.approved_at', [$s, $e]);
			})
			->when($selectedZillaId, fn($q) => $q->where('ra.zilla_id', $selectedZillaId))
			->when($selectedUpazilaId, fn($q) => $q->where('ra.upazila_id', $selectedUpazilaId))
			->when($selectedUnionId, fn($q) => $q->where('ra.union_id', $selectedUnionId))
			->when($selectedProjectId, fn($q) => $q->where('ra.project_id', $selectedProjectId))
			->select([
				\Illuminate\Support\Facades\DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
				\Illuminate\Support\Facades\DB::raw('SUM(ra.approved_amount) as total_amount')
			])
			->groupBy('rt.id', 'rt.unit', 'rt.unit_bn')
			->get();

		$totalsByUnit = [];
		foreach ($totalsByUnitQuery as $row) {
			$unit = $row->unit ?: '';
			if (!isset($totalsByUnit[$unit])) {
				$totalsByUnit[$unit] = 0;
			}
			$totalsByUnit[$unit] += (float)$row->total_amount;
		}

		return [
			'distribution' => $distribution,
			'totalsByUnit' => $totalsByUnit,
			'pagination' => [
				'current_page' => 1,
				'total_pages' => 1,
				'total_items' => $totalItems,
				'has_previous' => false,
				'has_next' => false,
				'previous_page' => null,
				'next_page' => null,
			],
		];
	}

	/**
	 * Get project budget breakdown (same logic as DistributionController).
	 */
	private function getProjectBudgetBreakdown($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId)
	{
		$start = $selectedYear?->start_date;
		$end = $selectedYear?->end_date;

		$query = \App\Models\Project::with('reliefType')
			->when($selectedYear, fn($q) => $q->where('economic_year_id', $selectedYear->id))
			->when($selectedProjectId, fn($q) => $q->where('id', $selectedProjectId));

		$projects = $query->get();

		return $projects->map(function ($project) use ($selectedZillaId, $selectedUpazilaId, $start, $end) {
			$distributedQuery = \App\Models\ReliefApplication::where('status', 'approved')
				->where('project_id', $project->id)
				->when($selectedZillaId, fn($q) => $q->where('zilla_id', $selectedZillaId))
				->when($selectedUpazilaId, fn($q) => $q->where('upazila_id', $selectedUpazilaId))
				->when($start && $end, function ($q) use ($start, $end) {
					$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
					$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
					return $q->whereBetween('approved_at', [$s, $e]);
				});

			$distributedAmount = $distributedQuery->sum('approved_amount');
			$availableAmount = $project->allocated_amount - $distributedAmount;
			$utilizationPercentage = $project->allocated_amount > 0 ? ($distributedAmount / $project->allocated_amount) * 100 : 0;

			return [
				'project' => $project,
				'allocated_amount' => $project->allocated_amount,
				'distributed_amount' => $distributedAmount,
				'available_amount' => $availableAmount,
				'utilization_percentage' => $utilizationPercentage,
			];
		});
	}

	/**
	 * Get upazila distribution data (same logic as DistributionController).
	 */
	private function getUpazilaDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $search, $pageSize, $currentPage)
	{
		$start = $selectedYear?->start_date;
		$end = $selectedYear?->end_date;

		$query = \Illuminate\Support\Facades\DB::table('relief_applications as ra')
			->join('projects as p', 'ra.project_id', '=', 'p.id')
			->join('upazilas as u', 'ra.upazila_id', '=', 'u.id')
			->join('zillas as z', 'ra.zilla_id', '=', 'z.id')
			->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
			->where('ra.status', 'approved')
			->when($start && $end, function ($q) use ($start, $end) {
				$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
				$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
				return $q->whereBetween('ra.approved_at', [$s, $e]);
			})
			->when($selectedZillaId, fn($q) => $q->where('ra.zilla_id', $selectedZillaId))
			->when($selectedUpazilaId, fn($q) => $q->where('ra.upazila_id', $selectedUpazilaId))
			->when($search, function ($q) use ($search) {
				return $q->where(function ($subQuery) use ($search) {
					$subQuery->where('p.name', 'LIKE', "%{$search}%")
						->orWhere('u.name', 'LIKE', "%{$search}%")
						->orWhere('u.name_bn', 'LIKE', "%{$search}%")
						->orWhere('z.name', 'LIKE', "%{$search}%")
						->orWhere('rt.name', 'LIKE', "%{$search}%");
				});
			})
		// Calculate totals by unit first (before counting)
		$totalsQuery = \Illuminate\Support\Facades\DB::table('relief_applications as ra')
			->join('projects as p', 'ra.project_id', '=', 'p.id')
			->join('upazilas as u', 'ra.upazila_id', '=', 'u.id')
			->join('zillas as z', 'u.zilla_id', '=', 'z.id')
			->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
			->where('ra.status', 'approved')
			->when($start && $end, function ($q) use ($start, $end) {
				$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
				$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
				return $q->whereBetween('ra.approved_at', [$s, $e]);
			})
			->when($selectedZillaId, fn($q) => $q->where('ra.zilla_id', $selectedZillaId))
			->when($selectedUpazilaId, fn($q) => $q->where('ra.upazila_id', $selectedUpazilaId))
			->when($search, function ($q) use ($search) {
				return $q->where(function ($subQuery) use ($search) {
					$subQuery->where('p.name', 'LIKE', "%{$search}%")
						->orWhere('u.name', 'LIKE', "%{$search}%")
						->orWhere('u.name_bn', 'LIKE', "%{$search}%")
						->orWhere('z.name', 'LIKE', "%{$search}%")
						->orWhere('z.name_bn', 'LIKE', "%{$search}%");
				});
			})
			->select([
				\Illuminate\Support\Facades\DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
				\Illuminate\Support\Facades\DB::raw('SUM(ra.approved_amount) as total_amount')
			])
			->groupBy('rt.id', 'rt.unit', 'rt.unit_bn')
			->get();

		$totalsByUnit = [];
		foreach ($totalsQuery as $row) {
			$unit = $row->unit ?: '';
			if (!isset($totalsByUnit[$unit])) {
				$totalsByUnit[$unit] = 0;
			}
			$totalsByUnit[$unit] += (float)$row->total_amount;
		}

		// Count total items for pagination (number of distinct groups) - without ORDER BY
		$countQuery = (clone $query)->select([
				'p.id',
				'u.id',
				'z.id'
			])
			->groupBy('p.id', 'u.id', 'z.id')
			->get();
		$totalItems = $countQuery->count();
		$totalPages = ceil($totalItems / $pageSize);
		$offset = ($currentPage - 1) * $pageSize;

		// Now get the actual data with ORDER BY
		$data = $query->select([
				'p.id as project_id',
				'p.name as project_name',
				'u.id as upazila_id',
				'u.name as upazila_name',
				'u.name_bn as upazila_name_bn',
				'z.name as zilla_name',
				'rt.name as relief_type_name',
				\Illuminate\Support\Facades\DB::raw('SUM(ra.approved_amount) as total_amount'),
				\Illuminate\Support\Facades\DB::raw('COUNT(ra.id) as application_count')
			])
			->groupBy(['p.id', 'p.name', 'u.id', 'u.name', 'u.name_bn', 'z.name', 'rt.name'])
			->orderBy('total_amount', 'desc')
			->skip($offset)
			->take($pageSize)
			->get();

		return [
			'data' => $data,
			'totalsByUnit' => $totalsByUnit,
			'pagination' => [
				'current_page' => $currentPage,
				'total_pages' => ceil($totalItems / $pageSize),
				'total_items' => $totalItems,
				'has_previous' => $currentPage > 1,
				'has_next' => $currentPage < ceil($totalItems / $pageSize),
				'previous_page' => $currentPage > 1 ? $currentPage - 1 : null,
				'next_page' => $currentPage < ceil($totalItems / $pageSize) ? $currentPage + 1 : null,
			],
		];
	}

	/**
	 * Get union distribution data (same logic as DistributionController).
	 */
	private function getUnionDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedUnionId, $search, $pageSize, $currentPage)
	{
		$start = $selectedYear?->start_date;
		$end = $selectedYear?->end_date;

		$query = \Illuminate\Support\Facades\DB::table('relief_applications as ra')
			->join('projects as p', 'ra.project_id', '=', 'p.id')
			->join('unions as un', 'ra.union_id', '=', 'un.id')
			->join('upazilas as u', 'ra.upazila_id', '=', 'u.id')
			->join('zillas as z', 'ra.zilla_id', '=', 'z.id')
			->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
			->where('ra.status', 'approved')
			->when($start && $end, function ($q) use ($start, $end) {
				$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
				$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
				return $q->whereBetween('ra.approved_at', [$s, $e]);
			})
			->when($selectedZillaId, fn($q) => $q->where('ra.zilla_id', $selectedZillaId))
			->when($selectedUpazilaId, fn($q) => $q->where('ra.upazila_id', $selectedUpazilaId))
			->when($selectedUnionId, fn($q) => $q->where('ra.union_id', $selectedUnionId))
			->when($search, function ($q) use ($search) {
				return $q->where(function ($subQuery) use ($search) {
					$subQuery->where('p.name', 'LIKE', "%{$search}%")
						->orWhere('un.name', 'LIKE', "%{$search}%")
						->orWhere('un.name_bn', 'LIKE', "%{$search}%")
						->orWhere('u.name', 'LIKE', "%{$search}%")
						->orWhere('z.name', 'LIKE', "%{$search}%")
						->orWhere('rt.name', 'LIKE', "%{$search}%");
				});
			});

		// Calculate totals by unit first (before counting)
		$totalsQuery = \Illuminate\Support\Facades\DB::table('relief_applications as ra')
			->join('projects as p', 'ra.project_id', '=', 'p.id')
			->join('unions as un', 'ra.union_id', '=', 'un.id')
			->join('upazilas as u', 'un.upazila_id', '=', 'u.id')
			->join('zillas as z', 'u.zilla_id', '=', 'z.id')
			->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
			->where('ra.status', 'approved')
			->when($start && $end, function ($q) use ($start, $end) {
				$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
				$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
				return $q->whereBetween('ra.approved_at', [$s, $e]);
			})
			->when($selectedZillaId, fn($q) => $q->where('ra.zilla_id', $selectedZillaId))
			->when($selectedUpazilaId, fn($q) => $q->where('ra.upazila_id', $selectedUpazilaId))
			->when($selectedUnionId, fn($q) => $q->where('ra.union_id', $selectedUnionId))
			->when($search, function ($q) use ($search) {
				return $q->where(function ($subQuery) use ($search) {
					$subQuery->where('p.name', 'LIKE', "%{$search}%")
						->orWhere('un.name', 'LIKE', "%{$search}%")
						->orWhere('un.name_bn', 'LIKE', "%{$search}%")
						->orWhere('u.name', 'LIKE', "%{$search}%")
						->orWhere('u.name_bn', 'LIKE', "%{$search}%")
						->orWhere('z.name', 'LIKE', "%{$search}%")
						->orWhere('z.name_bn', 'LIKE', "%{$search}%");
				});
			})
			->select([
				\Illuminate\Support\Facades\DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
				\Illuminate\Support\Facades\DB::raw('SUM(ra.approved_amount) as total_amount')
			])
			->groupBy('rt.id', 'rt.unit', 'rt.unit_bn')
			->get();

		$totalsByUnit = [];
		foreach ($totalsQuery as $row) {
			$unit = $row->unit ?: '';
			if (!isset($totalsByUnit[$unit])) {
				$totalsByUnit[$unit] = 0;
			}
			$totalsByUnit[$unit] += (float)$row->total_amount;
		}

		// Count total items for pagination (number of distinct groups) - without ORDER BY
		$countQuery = (clone $query)->select([
				'p.id',
				'un.id',
				'u.id',
				'z.id'
			])
			->groupBy('p.id', 'un.id', 'u.id', 'z.id')
			->get();
		$totalItems = $countQuery->count();
		$totalPages = ceil($totalItems / $pageSize);
		$offset = ($currentPage - 1) * $pageSize;

		// Now get the actual data with ORDER BY
		$data = $query->select([
				'p.id as project_id',
				'p.name as project_name',
				'un.id as union_id',
				'un.name as union_name',
				'un.name_bn as union_name_bn',
				'u.name as upazila_name',
				'z.name as zilla_name',
				'rt.name as relief_type_name',
				\Illuminate\Support\Facades\DB::raw('SUM(ra.approved_amount) as total_amount'),
				\Illuminate\Support\Facades\DB::raw('COUNT(ra.id) as application_count')
			])
			->groupBy(['p.id', 'p.name', 'un.id', 'un.name', 'un.name_bn', 'u.name', 'z.name', 'rt.name'])
			->orderBy('total_amount', 'desc')
			->skip($offset)
			->take($pageSize)
			->get();

		return [
			'data' => $data,
			'totalsByUnit' => $totalsByUnit,
			'pagination' => [
				'current_page' => $currentPage,
				'total_pages' => ceil($totalItems / $pageSize),
				'total_items' => $totalItems,
				'has_previous' => $currentPage > 1,
				'has_next' => $currentPage < ceil($totalItems / $pageSize),
				'previous_page' => $currentPage > 1 ? $currentPage - 1 : null,
				'next_page' => $currentPage < ceil($totalItems / $pageSize) ? $currentPage + 1 : null,
			],
		];
	}

	/**
	 * Get duplicates distribution data (same logic as DistributionController).
	 */
	private function getDuplicatesDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $search, $pageSize, $currentPage)
	{
		$start = $selectedYear?->start_date;
		$end = $selectedYear?->end_date;

		$query = \Illuminate\Support\Facades\DB::table('relief_applications as ra')
			->join('projects as p', 'ra.project_id', '=', 'p.id')
			->join('organization_types as ot', 'ra.organization_type_id', '=', 'ot.id')
			->join('zillas as z', 'ra.zilla_id', '=', 'z.id')
			->join('upazilas as u', 'ra.upazila_id', '=', 'u.id')
			->where('ra.status', 'approved')
			->when($start && $end, function ($q) use ($start, $end) {
				$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
				$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
				return $q->whereBetween('ra.approved_at', [$s, $e]);
			})
			->when($selectedZillaId, fn($q) => $q->where('ra.zilla_id', $selectedZillaId))
			->when($selectedUpazilaId, fn($q) => $q->where('ra.upazila_id', $selectedUpazilaId))
			->when($search, function ($q) use ($search) {
				return $q->where(function ($subQuery) use ($search) {
					$subQuery->where('ra.organization_name', 'LIKE', "%{$search}%")
						->orWhere('p.name', 'LIKE', "%{$search}%")
						->orWhere('ot.name', 'LIKE', "%{$search}%")
						->orWhere('z.name', 'LIKE', "%{$search}%")
						->orWhere('u.name', 'LIKE', "%{$search}%");
				});
			})
			->select([
				'ra.organization_name',
				'ot.name as organization_type_name',
				'z.name as zilla_name',
				'u.name as upazila_name',
				\Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT ra.project_id) as project_count'),
				\Illuminate\Support\Facades\DB::raw('SUM(ra.approved_amount) as total_amount'),
				\Illuminate\Support\Facades\DB::raw('COUNT(ra.id) as application_count')
			])
			->groupBy(['ra.organization_name', 'ot.name', 'z.name', 'u.name'])
			->havingRaw('COUNT(DISTINCT ra.project_id) > 1')
			->orderBy('total_amount', 'desc');

		// Calculate totals by unit for duplicates (based on approved amounts matching filters)
		$totalsByUnitQuery = \Illuminate\Support\Facades\DB::table('relief_applications as ra')
			->leftJoin('projects as p', 'ra.project_id', '=', 'p.id')
			->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
			->when($selectedYear, function ($q) use ($selectedYear) {
				return $q->where('p.economic_year_id', $selectedYear->id);
			})
			->where('ra.status', 'approved')
			->whereNotNull('ra.organization_name')
			->where('ra.organization_name', '!=', '')
			->select([
				\Illuminate\Support\Facades\DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
				\Illuminate\Support\Facades\DB::raw('SUM(ra.approved_amount) as total_amount')
			])
			->groupBy('rt.id', 'rt.unit', 'rt.unit_bn')
			->get();

		$totalsByUnit = [];
		foreach ($totalsByUnitQuery as $row) {
			$unit = $row->unit ?: '';
			if (!isset($totalsByUnit[$unit])) {
				$totalsByUnit[$unit] = 0;
			}
			$totalsByUnit[$unit] += (float)$row->total_amount;
		}

		$totalItems = $query->count();
		$data = $query->skip(($currentPage - 1) * $pageSize)->take($pageSize)->get();

		return [
			'data' => $data,
			'totalsByUnit' => $totalsByUnit,
			'pagination' => [
				'current_page' => $currentPage,
				'total_pages' => ceil($totalItems / $pageSize),
				'total_items' => $totalItems,
				'has_previous' => $currentPage > 1,
				'has_next' => $currentPage < ceil($totalItems / $pageSize),
				'previous_page' => $currentPage > 1 ? $currentPage - 1 : null,
				'next_page' => $currentPage < ceil($totalItems / $pageSize) ? $currentPage + 1 : null,
			],
		];
	}

	/**
	 * Get project allocations data (same logic as DistributionController).
	 */
	private function getProjectAllocationsData($selectedYear, $search, $pageSize, $currentPage)
	{
		$start = $selectedYear?->start_date;
		$end = $selectedYear?->end_date;

		$baseQuery = \App\Models\Project::with(['reliefType', 'economicYear'])
			->when($selectedYear, fn($q) => $q->where('economic_year_id', $selectedYear->id))
			->where('allocated_amount', '>', 0)
			->when($search, function ($q) use ($search) {
				return $q->where(function ($subQuery) use ($search) {
					$subQuery->where('name', 'LIKE', "%{$search}%")
						->orWhereHas('reliefType', function ($rtQuery) use ($search) {
							$rtQuery->where('name', 'LIKE', "%{$search}%")
								->orWhere('name_bn', 'LIKE', "%{$search}%");
						});
				});
			});

		// Calculate totals by unit based on allocated_amount
		$totalsByUnitQuery = (clone $baseQuery)->leftJoin('relief_types as rt', 'projects.relief_type_id', '=', 'rt.id')
			->select([
				\Illuminate\Support\Facades\DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
				\Illuminate\Support\Facades\DB::raw('SUM(projects.allocated_amount) as total_amount')
			])
			->groupBy('rt.id', 'rt.unit', 'rt.unit_bn')
			->get();

		$totalsByUnit = [];
		foreach ($totalsByUnitQuery as $row) {
			$unit = $row->unit ?: '';
			if (!isset($totalsByUnit[$unit])) {
				$totalsByUnit[$unit] = 0;
			}
			$totalsByUnit[$unit] += (float)$row->total_amount;
		}

		$query = (clone $baseQuery)->orderByDesc('allocated_amount');
		$totalItems = $query->count();
		$projects = $query->skip(($currentPage - 1) * $pageSize)->take($pageSize)->get();

		$data = $projects->map(function ($project) use ($start, $end) {
			$applicationsQuery = \App\Models\ReliefApplication::where('status', 'approved')
				->where('project_id', $project->id)
				->when($start && $end, function ($q) use ($start, $end) {
					$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
					$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
					return $q->whereBetween('approved_at', [$s, $e]);
				});

			$totalApplications = $applicationsQuery->count();
			$totalDistributed = $applicationsQuery->sum('approved_amount');
			$availableAmount = $project->allocated_amount - $totalDistributed;
			$utilizationPercentage = $project->allocated_amount > 0 ? ($totalDistributed / $project->allocated_amount) * 100 : 0;

			return [
				'project' => $project,
				'total_applications' => $totalApplications,
				'total_distributed' => $totalDistributed,
				'available_amount' => $availableAmount,
				'utilization_percentage' => $utilizationPercentage,
			];
		});

		return [
			'data' => $data,
			'totalsByUnit' => $totalsByUnit,
			'pagination' => [
				'current_page' => $currentPage,
				'total_pages' => ceil($totalItems / $pageSize),
				'total_items' => $totalItems,
				'has_previous' => $currentPage > 1,
				'has_next' => $currentPage < ceil($totalItems / $pageSize),
				'previous_page' => $currentPage > 1 ? $currentPage - 1 : null,
				'next_page' => $currentPage < ceil($totalItems / $pageSize) ? $currentPage + 1 : null,
			],
		];
	}
}