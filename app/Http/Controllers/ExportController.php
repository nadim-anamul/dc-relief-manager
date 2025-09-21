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
			'zilla_id', 'start_date', 'end_date'
		]);

		$query = ReliefApplication::with([
			'organizationType', 'zilla', 'upazila', 'union', 'ward', 
			'reliefType', 'project', 'approvedBy', 'rejectedBy',
			'createdBy', 'updatedBy'
		]);

		// Apply filters
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

		if (isset($filters['start_date'])) {
			$query->where('date', '>=', $filters['start_date']);
		}

		if (isset($filters['end_date'])) {
			$query->where('date', '<=', $filters['end_date']);
		}

		$reliefApplications = $query->orderBy('created_at', 'desc')->get();

		$pdf = Pdf::loadView('exports.relief-applications-pdf', [
			'reliefApplications' => $reliefApplications,
			'filters' => $filters,
			'exportDate' => now(),
		]);

		$fileName = 'relief_applications_' . now()->format('Y-m-d_H-i-s') . '.pdf';

		return $pdf->download($fileName);
	}

	/**
	 * Export project summary to Excel.
	 */
	public function exportProjectSummaryExcel(Request $request)
	{
		$filters = $request->only([
			'economic_year_id', 'relief_type_id', 'start_date', 'end_date'
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
			'economic_year_id', 'relief_type_id', 'start_date', 'end_date'
		]);

		$query = Project::with([
			'economicYear', 'reliefType', 'createdBy', 'updatedBy'
		]);

		// Apply filters
		if (isset($filters['economic_year_id'])) {
			$query->where('economic_year_id', $filters['economic_year_id']);
		}

		if (isset($filters['relief_type_id'])) {
			$query->where('relief_type_id', $filters['relief_type_id']);
		}

		if (isset($filters['start_date'])) {
			$query->where('start_date', '>=', $filters['start_date']);
		}

		if (isset($filters['end_date'])) {
			$query->where('end_date', '<=', $filters['end_date']);
		}

		$projects = $query->orderBy('name')->get();

		// Calculate summary statistics
		$totalProjects = $projects->count();
		$totalBudget = $projects->sum('budget');
		$activeProjects = $projects->where('is_active', true)->count();
		$completedProjects = $projects->where('is_completed', true)->count();

		$pdf = Pdf::loadView('exports.project-summary-pdf', [
			'projects' => $projects,
			'filters' => $filters,
			'exportDate' => now(),
			'totalProjects' => $totalProjects,
			'totalBudget' => $totalBudget,
			'activeProjects' => $activeProjects,
			'completedProjects' => $completedProjects,
		]);

		$fileName = 'project_summary_' . now()->format('Y-m-d_H-i-s') . '.pdf';

		return $pdf->download($fileName);
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
		]);

		$fileName = 'area_wise_relief_' . now()->format('Y-m-d_H-i-s') . '.pdf';

		return $pdf->download($fileName);
	}
}