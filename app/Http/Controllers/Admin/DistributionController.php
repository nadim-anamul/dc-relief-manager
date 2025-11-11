<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReliefApplication;
use App\Models\Project;
use App\Models\Zilla;
use App\Models\EconomicYear;
use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    /**
     * Display consolidated distribution page with all filters.
     */
    public function consolidated(Request $request): View
    {
        $years = EconomicYear::orderByDesc('start_date')->get();
        $selectedYear = $this->resolveSelectedYear($request, $years);
        
        $zillas = Zilla::orderBy('name')->get(['id', 'name', 'name_bn']);
        $selectedZillaId = $this->resolveSelectedZilla($request, $zillas);
        
        $selectedUpazilaId = $request->integer('upazila_id');
        $selectedUnionId = $request->integer('union_id');
        $selectedProjectId = $request->integer('project_id');
        $pageSize = 25;
        $currentPage = $request->integer('page', 1);

        $data = $this->getConsolidatedData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedUnionId, $selectedProjectId);
        $chartData = $this->getConsolidatedChartData($data['distribution'], $selectedZillaId, $selectedUpazilaId, $selectedUnionId, $selectedZillaId);
        $projectBudgetBreakdown = $this->getProjectBudgetBreakdown($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId);

        $upazilas = $selectedZillaId ? Upazila::where('zilla_id', $selectedZillaId)->orderBy('name')->get(['id', 'name', 'name_bn']) : collect();
        $unions = $selectedUpazilaId ? Union::where('upazila_id', $selectedUpazilaId)->orderBy('name')->get(['id', 'name', 'name_bn']) : collect();
        $projects = Project::forEconomicYear($selectedYear?->id)->orderBy('name')->get(['id', 'name']);

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

        return view('admin.distributions.consolidated', [
            'years' => $years,
            'selectedYearId' => $selectedYear?->id,
            'zillas' => $zillas,
            'selectedZillaId' => $selectedZillaId,
            'upazilas' => $upazilas,
            'selectedUpazilaId' => $selectedUpazilaId,
            'unions' => $unions,
            'selectedUnionId' => $selectedUnionId,
            'projects' => $projects,
            'selectedProjectId' => $selectedProjectId,
            'data' => $data,
            'chartData' => $chartData,
            'projectBudgetBreakdown' => $projectBudgetBreakdown,
            'projectUnits' => $projectUnits,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * Display Project × Upazila × Union Distribution page.
     */
    public function projectUpazilaUnion(Request $request): View
    {
        $years = EconomicYear::orderByDesc('start_date')->get();
        $selectedYear = $this->resolveSelectedYear($request, $years);
        
        $zillas = Zilla::orderBy('name')->get(['id', 'name', 'name_bn']);
        $selectedZillaId = $this->resolveSelectedZilla($request, $zillas);
        
        $selectedUpazilaId = $request->integer('upazila_id');
        $selectedProjectId = $request->integer('project_id');
        $pageSize = 20;
        $currentPage = $request->integer('page', 1);

        $data = $this->getProjectUpazilaUnionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId, $pageSize, $currentPage);
        $chartData = $this->getProjectUpazilaUnionChartData($data['distribution']);
        $unionChartData = $this->getUnionReliefChartData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId);
        $upazilaChartData = $this->getUpazilaReliefChartData($selectedYear, $selectedZillaId, $selectedProjectId);
        $projectBudgetBreakdown = $this->getProjectBudgetBreakdown($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId);

        $upazilas = $selectedZillaId ? Upazila::where('zilla_id', $selectedZillaId)->orderBy('name')->get(['id', 'name', 'name_bn']) : collect();
        $projects = Project::forEconomicYear($selectedYear?->id)->orderBy('name')->get(['id', 'name']);

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

        return view('admin.distributions.project-upazila-union', [
            'years' => $years,
            'selectedYearId' => $selectedYear?->id,
            'zillas' => $zillas,
            'selectedZillaId' => $selectedZillaId,
            'upazilas' => $upazilas,
            'selectedUpazilaId' => $selectedUpazilaId,
            'projects' => $projects,
            'selectedProjectId' => $selectedProjectId,
            'data' => $data,
            'chartData' => $chartData,
            'unionChartData' => $unionChartData,
            'upazilaChartData' => $upazilaChartData,
            'projectBudgetBreakdown' => $projectBudgetBreakdown,
            'projectUnits' => $projectUnits,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * Display Project × Upazila Distribution page.
     */
    public function projectUpazila(Request $request): View
    {
        $years = EconomicYear::orderByDesc('start_date')->get();
        $selectedYear = $this->resolveSelectedYear($request, $years);
        
        $zillas = Zilla::orderBy('name')->get(['id', 'name']);
        $selectedZillaId = $this->resolveSelectedZilla($request, $zillas);
        
        $selectedProjectId = $request->integer('project_id');
        $pageSize = 20;
        $currentPage = $request->integer('page', 1);

        $data = $this->getProjectUpazilaData($selectedYear, $selectedZillaId, $selectedProjectId, $pageSize, $currentPage);
        $chartData = $this->getProjectUpazilaChartData($data['distribution']);
        $upazilaChartData = $this->getUpazilaReliefChartData($selectedYear, $selectedZillaId, $selectedProjectId);
        $projectBudgetBreakdown = $this->getProjectBudgetBreakdown($selectedYear, $selectedZillaId, null, $selectedProjectId);

        $projects = Project::forEconomicYear($selectedYear?->id)->orderBy('name')->get(['id', 'name']);

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

        return view('admin.distributions.project-upazila', [
            'years' => $years,
            'selectedYearId' => $selectedYear?->id,
            'zillas' => $zillas,
            'selectedZillaId' => $selectedZillaId,
            'projects' => $projects,
            'selectedProjectId' => $selectedProjectId,
            'data' => $data,
            'chartData' => $chartData,
            'upazilaChartData' => $upazilaChartData,
            'projectBudgetBreakdown' => $projectBudgetBreakdown,
            'projectUnits' => $projectUnits,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * Display Union-wise Allocation Summary page.
     */
    public function unionSummary(Request $request): View
    {
        $years = EconomicYear::orderByDesc('start_date')->get();
        $selectedYear = $this->resolveSelectedYear($request, $years);
        
        $zillas = Zilla::orderBy('name')->get(['id', 'name']);
        $selectedZillaId = $this->resolveSelectedZilla($request, $zillas);
        $selectedProjectId = $request->integer('project_id');
        
        $selectedUpazilaId = $request->integer('upazila_id');
        $pageSize = 20;
        $currentPage = $request->integer('page', 1);

        $data = $this->getUnionSummaryData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId, $pageSize, $currentPage);
        $chartData = $this->getUnionSummaryChartData($data['summary']);
        $projectBudgetBreakdown = $this->getProjectBudgetBreakdown($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId);

        $upazilas = $selectedZillaId ? Upazila::where('zilla_id', $selectedZillaId)->orderBy('name')->get(['id', 'name']) : collect();
        $projects = Project::forEconomicYear($selectedYear?->id)->orderBy('name')->get(['id', 'name']);

        // Project units (for formatting amounts based on project relief type)
        $projectUnits = Project::with('reliefType')->get()->mapWithKeys(function ($p) {
            $unit = $p->reliefType?->unit_bn ?? $p->reliefType?->unit ?? '';
            $isMoney = in_array($unit, ['টাকা', 'Taka']);
            return [$p->id => ['unit' => $unit, 'is_money' => $isMoney]];
        });

        return view('admin.distributions.union-summary', [
            'years' => $years,
            'selectedYearId' => $selectedYear?->id,
            'zillas' => $zillas,
            'selectedZillaId' => $selectedZillaId,
            'upazilas' => $upazilas,
            'selectedUpazilaId' => $selectedUpazilaId,
            'projects' => $projects,
            'selectedProjectId' => $selectedProjectId,
            'data' => $data,
            'chartData' => $chartData,
            'projectBudgetBreakdown' => $projectBudgetBreakdown,
            'projectUnits' => $projectUnits,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * Display Area-wise Allocation Summary page.
     */
    public function areaSummary(Request $request): View
    {
        $years = EconomicYear::orderByDesc('start_date')->get();
        $selectedYear = $this->resolveSelectedYear($request, $years);
        
        $zillas = Zilla::orderBy('name')->get(['id', 'name']);
        $selectedZillaId = $this->resolveSelectedZilla($request, $zillas);
        $selectedProjectId = $request->integer('project_id');
        
        $pageSize = 20;
        $currentPage = $request->integer('page', 1);

        $data = $this->getAreaSummaryData($selectedYear, $selectedZillaId, $selectedProjectId, $pageSize, $currentPage);
        $chartData = $this->getAreaSummaryChartData($data['summary']);
        $projectBudgetBreakdown = $this->getProjectBudgetBreakdown($selectedYear, $selectedZillaId, null, $selectedProjectId);

        $projects = Project::forEconomicYear($selectedYear?->id)->orderBy('name')->get(['id', 'name']);

        // Project units (for formatting amounts based on project relief type)
        $projectUnits = Project::with('reliefType')->get()->mapWithKeys(function ($p) {
            $unit = $p->reliefType?->unit_bn ?? $p->reliefType?->unit ?? '';
            $isMoney = in_array($unit, ['টাকা', 'Taka']);
            return [$p->id => ['unit' => $unit, 'is_money' => $isMoney]];
        });

        return view('admin.distributions.area-summary', [
            'years' => $years,
            'selectedYearId' => $selectedYear?->id,
            'zillas' => $zillas,
            'selectedZillaId' => $selectedZillaId,
            'projects' => $projects,
            'selectedProjectId' => $selectedProjectId,
            'data' => $data,
            'chartData' => $chartData,
            'projectBudgetBreakdown' => $projectBudgetBreakdown,
            'projectUnits' => $projectUnits,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * Get Project × Upazila × Union distribution data.
     */
    private function getProjectUpazilaUnionData(?EconomicYear $year, ?int $zillaId, ?int $upazilaId, ?int $projectId, int $pageSize, int $currentPage): array
    {
        $start = $year?->start_date;
        $end = $year?->end_date;

        $query = ReliefApplication::where('status', 'approved')
            ->with(['project', 'zilla', 'upazila', 'union', 'organizationType'])
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('zilla_id', $zillaId))
            ->when($upazilaId, fn($q) => $q->where('upazila_id', $upazilaId))
            ->when($projectId, fn($q) => $q->where('project_id', $projectId));

        // Load all matching records for client-side DataTables pagination/search
        $distribution = $query->orderByDesc('approved_amount')->get();
        $totalItems = $distribution->count();

        return [
            'distribution' => $distribution,
            'pagination' => [
                'current_page' => $currentPage,
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
     * Get Project × Upazila distribution data.
     */
    private function getProjectUpazilaData(?EconomicYear $year, ?int $zillaId, ?int $projectId, int $pageSize, int $currentPage): array
    {
        $start = $year?->start_date;
        $end = $year?->end_date;

        $query = ReliefApplication::where('status', 'approved')
            ->with(['project', 'zilla', 'upazila', 'organizationType'])
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('zilla_id', $zillaId))
            ->when($projectId, fn($q) => $q->where('project_id', $projectId));

        $totalItems = $query->count();
        $distribution = $query->orderByDesc('approved_amount')
            ->skip(($currentPage - 1) * $pageSize)
            ->take($pageSize)
            ->get();

        return [
            'distribution' => $distribution,
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
     * Get Union summary data.
     */
    private function getUnionSummaryData(?EconomicYear $year, ?int $zillaId, ?int $upazilaId, ?int $projectId, int $pageSize, int $currentPage): array
    {
        $start = $year?->start_date;
        $end = $year?->end_date;

        $query = ReliefApplication::where('status', 'approved')
            ->with(['union', 'upazila', 'zilla', 'project'])
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('zilla_id', $zillaId))
            ->when($upazilaId, fn($q) => $q->where('upazila_id', $upazilaId))
            ->when($projectId, fn($q) => $q->where('project_id', $projectId));

        $summary = $query->selectRaw('union_id, upazila_id, zilla_id, SUM(approved_amount) as total_amount, COUNT(*) as application_count')
            ->groupBy('union_id', 'upazila_id', 'zilla_id')
            ->orderByDesc('total_amount')
            ->get();

        $totalItems = $summary->count();
        $paginatedSummary = $summary->skip(($currentPage - 1) * $pageSize)->take($pageSize);

        // Get detailed data for the table
        $detailedQuery = ReliefApplication::where('status', 'approved')
            ->with(['union', 'upazila', 'zilla', 'project', 'organizationType'])
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('zilla_id', $zillaId))
            ->when($upazilaId, fn($q) => $q->where('upazila_id', $upazilaId))
            ->when($projectId, fn($q) => $q->where('project_id', $projectId));

        $detailed = $detailedQuery->orderBy('approved_at', 'desc')->get();

        return [
            'summary' => $paginatedSummary,
            'detailed' => $detailed,
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
     * Get Area summary data.
     */
    private function getAreaSummaryData(?EconomicYear $year, ?int $zillaId, ?int $projectId, int $pageSize, int $currentPage): array
    {
        $start = $year?->start_date;
        $end = $year?->end_date;

        $query = ReliefApplication::where('status', 'approved')
            ->with(['upazila', 'zilla', 'project'])
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('zilla_id', $zillaId))
            ->when($projectId, fn($q) => $q->where('project_id', $projectId));

        $summary = $query->selectRaw('upazila_id, zilla_id, SUM(approved_amount) as total_amount, COUNT(*) as application_count')
            ->groupBy('upazila_id', 'zilla_id')
            ->orderByDesc('total_amount')
            ->get();

        $totalItems = $summary->count();
        $paginatedSummary = $summary->skip(($currentPage - 1) * $pageSize)->take($pageSize);

        // Get detailed data for the table
        $detailedQuery = ReliefApplication::where('status', 'approved')
            ->with(['upazila', 'zilla', 'project', 'organizationType'])
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('zilla_id', $zillaId))
            ->when($projectId, fn($q) => $q->where('project_id', $projectId));

        $detailed = $detailedQuery->orderBy('approved_at', 'desc')->get();

        return [
            'summary' => $paginatedSummary,
            'detailed' => $detailed,
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
     * Get chart data for Project × Upazila × Union distribution.
     */
    private function getProjectUpazilaUnionChartData($distribution)
    {
        $projectData = $distribution->groupBy(function ($item) {
            return $item->project?->name ?? 'Unknown Project';
        })->map(function ($items) {
            return $items->sum('approved_amount');
        });

        $zillaData = $distribution->groupBy(function ($item) {
            return $item->zilla?->name ?? 'Unknown Zilla';
        })->map(function ($items) {
            return $items->sum('approved_amount');
        });

        return [
            'projectData' => [
                'labels' => $projectData->keys()->toArray(),
                'data' => $projectData->values()->toArray(),
            ],
            'zillaData' => [
                'labels' => $zillaData->keys()->toArray(),
                'data' => $zillaData->values()->toArray(),
            ],
        ];
    }

    /**
     * Get chart data for Project × Upazila distribution.
     */
    private function getProjectUpazilaChartData($distribution)
    {
        $projectData = $distribution->groupBy(function ($item) {
            return $item->project?->name ?? 'Unknown Project';
        })->map(function ($items) {
            return $items->sum('approved_amount');
        });

        $upazilaData = $distribution->groupBy(function ($item) {
            return $item->upazila?->name ?? 'Unknown Upazila';
        })->map(function ($items) {
            return $items->sum('approved_amount');
        });

        return [
            'projectData' => [
                'labels' => $projectData->keys()->toArray(),
                'data' => $projectData->values()->toArray(),
            ],
            'upazilaData' => [
                'labels' => $upazilaData->keys()->toArray(),
                'data' => $upazilaData->values()->toArray(),
            ],
        ];
    }

    /**
     * Get Upazila relief distribution chart data.
     */
    private function getUpazilaReliefChartData(?EconomicYear $year, ?int $zillaId, ?int $projectId): array
    {
        $start = $year?->start_date;
        $end = $year?->end_date;

        $query = ReliefApplication::where('status', 'approved')
            ->join('upazilas', 'relief_applications.upazila_id', '=', 'upazilas.id')
            ->select('upazilas.name as upazila_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'), DB::raw('COUNT(*) as application_count'))
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('relief_applications.approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('relief_applications.zilla_id', $zillaId))
            ->when($projectId, fn($q) => $q->where('relief_applications.project_id', $projectId))
            ->groupBy('upazilas.id', 'upazilas.name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        return [
            'labels' => $query->pluck('upazila_name')->toArray(),
            'data' => $query->pluck('total_amount')->toArray(),
            'applicationCounts' => $query->pluck('application_count')->toArray(),
        ];
    }

    /**
     * Get Union relief distribution chart data.
     */
    private function getUnionReliefChartData(?EconomicYear $year, ?int $zillaId, ?int $upazilaId, ?int $projectId): array
    {
        $start = $year?->start_date;
        $end = $year?->end_date;

        $query = ReliefApplication::where('status', 'approved')
            ->join('unions', 'relief_applications.union_id', '=', 'unions.id')
            ->select('unions.name as union_name', DB::raw('SUM(relief_applications.approved_amount) as total_amount'), DB::raw('COUNT(*) as application_count'))
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('relief_applications.approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('relief_applications.zilla_id', $zillaId))
            ->when($upazilaId, fn($q) => $q->where('relief_applications.upazila_id', $upazilaId))
            ->when($projectId, fn($q) => $q->where('relief_applications.project_id', $projectId))
            ->groupBy('unions.id', 'unions.name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        return [
            'labels' => $query->pluck('union_name')->toArray(),
            'data' => $query->pluck('total_amount')->toArray(),
            'applicationCounts' => $query->pluck('application_count')->toArray(),
        ];
    }

    /**
     * Get project budget breakdown data.
     */
    private function getProjectBudgetBreakdown(?EconomicYear $year, ?int $zillaId, ?int $upazilaId, ?int $projectId): array
    {
        $start = $year?->start_date;
        $end = $year?->end_date;

        // Get projects with their budget information
        $projectsQuery = Project::with(['reliefType', 'economicYear'])
            ->when($year, fn($q) => $q->forEconomicYear($year->id))
            ->when($projectId, fn($q) => $q->where('id', $projectId));

        $projects = $projectsQuery->get();

        // Calculate distributed amounts for each project
        $distributedAmounts = ReliefApplication::where('status', 'approved')
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('zilla_id', $zillaId))
            ->when($upazilaId, fn($q) => $q->where('upazila_id', $upazilaId))
            ->when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->selectRaw('project_id, SUM(approved_amount) as distributed_amount')
            ->groupBy('project_id')
            ->pluck('distributed_amount', 'project_id');

        return $projects->map(function ($project) use ($distributedAmounts) {
            $unit = $project->reliefType?->unit_bn ?? $project->reliefType?->unit ?? '';
            $allocated = (float)($project->allocated_amount ?? 0);
            $distributed = (float)($distributedAmounts[$project->id] ?? 0);
            $available = max(0.0, $allocated - $distributed);

            $format = function ($n) use ($unit) {
                $amount = number_format((float)$n, 2);
                return in_array($unit, ['টাকা', 'Taka']) ? ('৳' . $amount) : ($amount . ' ' . $unit);
            };

            return [
                'id' => $project->id,
                'name' => $project->name,
                'relief_type' => $project->reliefType,
                'allocated_amount' => $allocated,
                'distributed_amount' => $distributed,
                'available_amount' => $available,
                'formatted_allocated' => $format($allocated),
                'formatted_distributed' => $format($distributed),
                'formatted_available' => $format($available),
                'used_ratio' => $allocated > 0 ? round($distributed / $allocated, 4) : 0,
            ];
        })->sortByDesc('allocated_amount')->values()->toArray();
    }

    /**
     * Get chart data for Union summary.
     */
    private function getUnionSummaryChartData($summary)
    {
        $zillaData = $summary->groupBy(function ($item) {
            return $item->zilla?->name ?? 'Unknown Zilla';
        })->map(function ($items) {
            return $items->sum('total_amount');
        });

        $upazilaData = $summary->groupBy(function ($item) {
            return $item->upazila?->name ?? 'Unknown Upazila';
        })->map(function ($items) {
            return $items->sum('total_amount');
        });

        return [
            'zillaData' => [
                'labels' => $zillaData->keys()->toArray(),
                'data' => $zillaData->values()->toArray(),
            ],
            'upazilaData' => [
                'labels' => $upazilaData->keys()->toArray(),
                'data' => $upazilaData->values()->toArray(),
            ],
        ];
    }

    /**
     * Get chart data for Area summary.
     */
    private function getAreaSummaryChartData($summary)
    {
        $zillaData = $summary->groupBy(function ($item) {
            return $item->zilla?->name ?? 'Unknown Zilla';
        })->map(function ($items) {
            return $items->sum('total_amount');
        });

        $upazilaData = $summary->groupBy(function ($item) {
            return $item->upazila?->name ?? 'Unknown Upazila';
        })->map(function ($items) {
            return $items->sum('total_amount');
        });

        return [
            'zillaData' => [
                'labels' => $zillaData->keys()->toArray(),
                'data' => $zillaData->values()->toArray(),
            ],
            'upazilaData' => [
                'labels' => $upazilaData->keys()->toArray(),
                'data' => $upazilaData->values()->toArray(),
            ],
        ];
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
        
        // Only default to current year when economic_year_id parameter is not present at all
        if (!$request->has('economic_year_id')) {
            return $years->firstWhere('is_current', true) ?? $years->first();
        }
        
        // If economic_year_id is present but empty (0), return null to show all years
        return null;
    }

    /**
     * Resolve selected zilla from request or default to single zilla if only one exists.
     */
    private function resolveSelectedZilla(Request $request, $zillas): ?int
    {
        $zillaId = $request->integer('zilla_id');
        if ($zillaId) {
            return $zillaId;
        }
        
        if ($zillas->count() === 1) {
            return $zillas->first()->id;
        }
        
        return null;
    }

    /**
     * Get consolidated distribution data with all filters.
     */
    private function getConsolidatedData(?EconomicYear $year, ?int $zillaId, ?int $upazilaId, ?int $unionId, ?int $projectId): array
    {
        $start = $year?->start_date;
        $end = $year?->end_date;

        $query = ReliefApplication::where('status', 'approved')
            ->with(['project.reliefType', 'zilla', 'upazila', 'union', 'organizationType'])
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('zilla_id', $zillaId))
            ->when($upazilaId, fn($q) => $q->where('upazila_id', $upazilaId))
            ->when($unionId, fn($q) => $q->where('union_id', $unionId))
            ->when($projectId, fn($q) => $q->where('project_id', $projectId));

        $distribution = $query->orderByDesc('approved_amount')->get();
        $totalItems = $distribution->count();

        // Calculate totals by unit
        $totalsByUnitQuery = DB::table('relief_applications as ra')
            ->leftJoin('projects as p', 'ra.project_id', '=', 'p.id')
            ->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
            ->where('ra.status', 'approved')
            ->when($start && $end, function ($q) use ($start, $end) {
                $s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
                $e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
                return $q->whereBetween('ra.approved_at', [$s, $e]);
            })
            ->when($zillaId, fn($q) => $q->where('ra.zilla_id', $zillaId))
            ->when($upazilaId, fn($q) => $q->where('ra.upazila_id', $upazilaId))
            ->when($unionId, fn($q) => $q->where('ra.union_id', $unionId))
            ->when($projectId, fn($q) => $q->where('ra.project_id', $projectId))
            ->select([
                DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
                DB::raw('SUM(ra.approved_amount) as total_amount')
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
     * Get consolidated chart data based on active filters.
     */
    private function getConsolidatedChartData($distribution, ?int $zillaId, ?int $upazilaId, ?int $unionId, ?int $selectedZillaId = null): array
    {
        $chartData = [];

        $projectData = $distribution->groupBy(function ($item) {
            return $item->project?->name ?? 'Unknown Project';
        })->map(function ($items) {
            return $items->sum('approved_amount');
        });
        $chartData['projectData'] = [
            'labels' => $projectData->keys()->toArray(),
            'data' => $projectData->values()->toArray(),
        ];

        if (!$zillaId) {
            $zillaData = $distribution->groupBy(function ($item) {
                return $item->zilla?->name ?? 'Unknown Zilla';
            })->map(function ($items) {
                return $items->sum('approved_amount');
            });
            $chartData['zillaData'] = [
                'labels' => $zillaData->keys()->toArray(),
                'data' => $zillaData->values()->toArray(),
            ];
        }

        if (!$upazilaId) {
            // Get all upazilas (like dashboard does)
            $allUpazilas = $selectedZillaId ? 
                Upazila::where('zilla_id', $selectedZillaId)->get() : 
                Upazila::all();
            
            // Get upazila names mapping
            $upazilaNames = $allUpazilas->mapWithKeys(function ($upazila) {
                return [$upazila->id => $upazila->name_bn ?: $upazila->name];
            });
            
            // Get distribution data grouped by upazila (count applications instead of amount)
            $upazilaDistributionData = $distribution->groupBy(function ($item) {
                return $item->upazila_id;
            })->map(function ($items) {
                return $items->count(); // Count applications instead of summing amounts
            });
            
            // Create chart data with all upazilas (including those with 0)
            $chartLabels = [];
            $chartDataValues = [];
            
            foreach ($allUpazilas as $upazila) {
                $chartLabels[] = $upazilaNames[$upazila->id] ?? "Upazila #{$upazila->id}";
                $chartDataValues[] = $upazilaDistributionData[$upazila->id] ?? 0;
            }
            
            $chartData['upazilaData'] = [
                'labels' => $chartLabels,
                'data' => $chartDataValues,
            ];
        }

        if (!$unionId && $upazilaId) {
            $unionData = $distribution->groupBy(function ($item) {
                return $item->union?->name ?? 'Unknown Union';
            })->map(function ($items) {
                return $items->sum('approved_amount');
            });
            $chartData['unionData'] = [
                'labels' => $unionData->keys()->toArray(),
                'data' => $unionData->values()->toArray(),
            ];
        }

        $orgTypeData = $distribution->groupBy(function ($item) {
            return $item->organizationType?->name ?? 'Unknown Organization Type';
        })->map(function ($items) {
            return $items->sum('approved_amount');
        });
        $chartData['orgTypeData'] = [
            'labels' => $orgTypeData->keys()->toArray(),
            'data' => $orgTypeData->values()->toArray(),
        ];

        return $chartData;
    }

    /**
     * Display detailed distribution page with filters and search.
     */
    public function detailed(Request $request, string $type): View
    {
        $years = EconomicYear::orderByDesc('start_date')->get();
        
        // For duplicates, don't default to current year - show all years
        if ($type === 'duplicates') {
            $selectedYear = $request->integer('economic_year_id') ? 
                $years->firstWhere('id', $request->integer('economic_year_id')) : null;
        } else {
            $selectedYear = $this->resolveSelectedYear($request, $years);
        }
        
        $zillas = Zilla::orderBy('name')->get(['id', 'name', 'name_bn']);
        $selectedZillaId = $this->resolveSelectedZilla($request, $zillas);
        
        $selectedUpazilaId = $request->integer('upazila_id');
        $selectedUnionId = $request->integer('union_id');
        $selectedProjectId = $request->integer('project_id');
        $search = $request->get('search', '');
        $pageSize = $request->integer('per_page', 25);
        $currentPage = $request->integer('page', 1);

        // Get the data based on type
        if ($type === 'upazila') {
            $data = $this->getUpazilaDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $search, $pageSize, $currentPage);
            $title = __('Project × Upazila Distribution');
            $description = __('Detailed distribution of relief across upazilas');
        } elseif ($type === 'union') {
            $data = $this->getUnionDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedUnionId, $search, $pageSize, $currentPage);
            $title = __('Project × Upazila × Union Distribution');
            $description = __('Detailed distribution of relief across unions');
        } elseif ($type === 'duplicates') {
            $data = $this->getDuplicateAllocationsData($selectedYear, $search, $pageSize, $currentPage);
            $title = __('Duplicate Allocations');
            $description = __('Organizations with multiple allocations across all economic years');
        } elseif ($type === 'projects') {
            $data = $this->getProjectAllocationsData($selectedYear, $search, $pageSize, $currentPage);
            $title = __('Active Project Allocations');
            $description = __('Current year project allocations with applications');
        } else {
            abort(404);
        }

        $upazilas = $selectedZillaId ? Upazila::where('zilla_id', $selectedZillaId)->orderBy('name')->get(['id', 'name', 'name_bn']) : collect();
        $unions = $selectedUpazilaId ? Union::where('upazila_id', $selectedUpazilaId)->orderBy('name')->get(['id', 'name', 'name_bn']) : collect();
        $projects = Project::forEconomicYear($selectedYear?->id)->orderBy('name')->get(['id', 'name']);

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

        return view('admin.distributions.detailed', compact(
            'type',
            'title',
            'description',
            'years',
            'selectedYear',
            'zillas',
            'selectedZillaId',
            'upazilas',
            'selectedUpazilaId',
            'unions',
            'selectedUnionId',
            'projects',
            'selectedProjectId',
            'search',
            'pageSize',
            'data',
            'projectUnits'
        ));
    }

    /**
     * Get upazila distribution data with pagination and search.
     */
    private function getUpazilaDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $search, $pageSize, $currentPage)
    {
        $query = DB::table('relief_applications as ra')
            ->join('projects as p', 'ra.project_id', '=', 'p.id')
            ->join('upazilas as u', 'ra.upazila_id', '=', 'u.id')
            ->join('zillas as z', 'u.zilla_id', '=', 'z.id')
            ->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
            ->where('ra.status', 'approved');

        if ($selectedYear) {
            $query->where('p.economic_year_id', $selectedYear->id);
        }

        if ($selectedZillaId) {
            $query->where('ra.zilla_id', $selectedZillaId);
        }

        if ($selectedUpazilaId) {
            $query->where('ra.upazila_id', $selectedUpazilaId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('p.name', 'LIKE', "%{$search}%")
                  ->orWhere('u.name', 'LIKE', "%{$search}%")
                  ->orWhere('u.name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('z.name', 'LIKE', "%{$search}%")
                  ->orWhere('z.name_bn', 'LIKE', "%{$search}%");
            });
        }

        // Calculate totals by unit from all applications matching the filters
        // Use the same base query but join with relief_types to get units
        $totalsQuery = DB::table('relief_applications as ra')
            ->join('projects as p', 'ra.project_id', '=', 'p.id')
            ->join('upazilas as u', 'ra.upazila_id', '=', 'u.id')
            ->join('zillas as z', 'u.zilla_id', '=', 'z.id')
            ->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
            ->where('ra.status', 'approved');

        if ($selectedYear) {
            $totalsQuery->where('p.economic_year_id', $selectedYear->id);
        }

        if ($selectedZillaId) {
            $totalsQuery->where('ra.zilla_id', $selectedZillaId);
        }

        if ($selectedUpazilaId) {
            $totalsQuery->where('ra.upazila_id', $selectedUpazilaId);
        }

        if ($search) {
            $totalsQuery->where(function ($q) use ($search) {
                $q->where('p.name', 'LIKE', "%{$search}%")
                  ->orWhere('u.name', 'LIKE', "%{$search}%")
                  ->orWhere('u.name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('z.name', 'LIKE', "%{$search}%")
                  ->orWhere('z.name_bn', 'LIKE', "%{$search}%");
            });
        }

        // Get totals by unit
        $totalsByUnitRaw = $totalsQuery->select([
                DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
                DB::raw('SUM(ra.approved_amount) as total_amount')
            ])
            ->groupBy('rt.id', 'rt.unit', 'rt.unit_bn')
            ->get();

        $totalsByUnit = [];
        foreach ($totalsByUnitRaw as $row) {
            $unit = $row->unit ?: '';
            if (!isset($totalsByUnit[$unit])) {
                $totalsByUnit[$unit] = 0;
            }
            $totalsByUnit[$unit] += $row->total_amount;
        }

        // Count total items for pagination (number of distinct groups)
        $allGroupedResults = (clone $query)->select([
                'ra.project_id',
                'ra.upazila_id',
                'ra.zilla_id'
            ])
            ->groupBy('ra.project_id', 'ra.upazila_id', 'ra.zilla_id')
            ->get();
        $totalItems = $allGroupedResults->count();
        $totalPages = ceil($totalItems / $pageSize);
        $offset = ($currentPage - 1) * $pageSize;

        $results = $query->select([
                'ra.project_id',
                'ra.upazila_id',
                'ra.zilla_id',
                DB::raw('SUM(ra.approved_amount) as total_amount'),
                DB::raw('COUNT(*) as application_count')
            ])
            ->groupBy('ra.project_id', 'ra.upazila_id', 'ra.zilla_id')
            ->orderByDesc('total_amount')
            ->offset($offset)
            ->limit($pageSize)
            ->get();

        // Get names for display with both English and Bangla
        $projectNames = Project::whereIn('id', $results->pluck('project_id'))->get(['id', 'name'])->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        });
        
        $upazilaNames = Upazila::whereIn('id', $results->pluck('upazila_id'))->get(['id', 'name', 'name_bn'])->mapWithKeys(function ($item) {
            return [$item->id => app()->isLocale('bn') ? ($item->name_bn ?: $item->name) : ($item->name ?: $item->name_bn)];
        });
        
        $zillaNames = Zilla::whereIn('id', $results->pluck('zilla_id'))->get(['id', 'name', 'name_bn'])->mapWithKeys(function ($item) {
            return [$item->id => app()->isLocale('bn') ? ($item->name_bn ?: $item->name) : ($item->name ?: $item->name_bn)];
        });

        return [
            'results' => $results,
            'projectNames' => $projectNames,
            'upazilaNames' => $upazilaNames,
            'zillaNames' => $zillaNames,
            'totalsByUnit' => $totalsByUnit,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'per_page' => $pageSize,
            ]
        ];
    }

    /**
     * Get union distribution data with pagination and search.
     */
    private function getUnionDistributionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedUnionId, $search, $pageSize, $currentPage)
    {
        $query = DB::table('relief_applications as ra')
            ->join('projects as p', 'ra.project_id', '=', 'p.id')
            ->join('unions as un', 'ra.union_id', '=', 'un.id')
            ->join('upazilas as u', 'un.upazila_id', '=', 'u.id')
            ->join('zillas as z', 'u.zilla_id', '=', 'z.id')
            ->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
            ->where('ra.status', 'approved');

        if ($selectedYear) {
            $query->where('p.economic_year_id', $selectedYear->id);
        }

        if ($selectedZillaId) {
            $query->where('ra.zilla_id', $selectedZillaId);
        }

        if ($selectedUpazilaId) {
            $query->where('ra.upazila_id', $selectedUpazilaId);
        }

        if ($selectedUnionId) {
            $query->where('ra.union_id', $selectedUnionId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('p.name', 'LIKE', "%{$search}%")
                  ->orWhere('un.name', 'LIKE', "%{$search}%")
                  ->orWhere('un.name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('u.name', 'LIKE', "%{$search}%")
                  ->orWhere('u.name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('z.name', 'LIKE', "%{$search}%")
                  ->orWhere('z.name_bn', 'LIKE', "%{$search}%");
            });
        }

        // Calculate totals by unit from all applications matching the filters
        $totalsQuery = DB::table('relief_applications as ra')
            ->join('projects as p', 'ra.project_id', '=', 'p.id')
            ->join('unions as un', 'ra.union_id', '=', 'un.id')
            ->join('upazilas as u', 'un.upazila_id', '=', 'u.id')
            ->join('zillas as z', 'u.zilla_id', '=', 'z.id')
            ->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
            ->where('ra.status', 'approved');

        if ($selectedYear) {
            $totalsQuery->where('p.economic_year_id', $selectedYear->id);
        }

        if ($selectedZillaId) {
            $totalsQuery->where('ra.zilla_id', $selectedZillaId);
        }

        if ($selectedUpazilaId) {
            $totalsQuery->where('ra.upazila_id', $selectedUpazilaId);
        }

        if ($selectedUnionId) {
            $totalsQuery->where('ra.union_id', $selectedUnionId);
        }

        if ($search) {
            $totalsQuery->where(function ($q) use ($search) {
                $q->where('p.name', 'LIKE', "%{$search}%")
                  ->orWhere('un.name', 'LIKE', "%{$search}%")
                  ->orWhere('un.name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('u.name', 'LIKE', "%{$search}%")
                  ->orWhere('u.name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('z.name', 'LIKE', "%{$search}%")
                  ->orWhere('z.name_bn', 'LIKE', "%{$search}%");
            });
        }

        // Get totals by unit
        $totalsByUnitRaw = $totalsQuery->select([
                DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
                DB::raw('SUM(ra.approved_amount) as total_amount')
            ])
            ->groupBy('rt.id', 'rt.unit', 'rt.unit_bn')
            ->get();

        $totalsByUnit = [];
        foreach ($totalsByUnitRaw as $row) {
            $unit = $row->unit ?: '';
            if (!isset($totalsByUnit[$unit])) {
                $totalsByUnit[$unit] = 0;
            }
            $totalsByUnit[$unit] += $row->total_amount;
        }

        // Count total items for pagination (number of distinct groups)
        $allGroupedResults = (clone $query)->select([
                'ra.project_id',
                'ra.union_id',
                'ra.upazila_id',
                'ra.zilla_id'
            ])
            ->groupBy('ra.project_id', 'ra.union_id', 'ra.upazila_id', 'ra.zilla_id')
            ->get();
        $totalItems = $allGroupedResults->count();
        $totalPages = ceil($totalItems / $pageSize);
        $offset = ($currentPage - 1) * $pageSize;

        $results = $query->select([
                'ra.project_id',
                'ra.union_id',
                'ra.upazila_id',
                'ra.zilla_id',
                DB::raw('SUM(ra.approved_amount) as total_amount'),
                DB::raw('COUNT(*) as application_count')
            ])
            ->groupBy('ra.project_id', 'ra.union_id', 'ra.upazila_id', 'ra.zilla_id')
            ->orderByDesc('total_amount')
            ->offset($offset)
            ->limit($pageSize)
            ->get();

        // Get names for display with both English and Bangla
        $projectNames = Project::whereIn('id', $results->pluck('project_id'))->get(['id', 'name'])->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        });
        
        $unionNames = Union::whereIn('id', $results->pluck('union_id'))->get(['id', 'name', 'name_bn'])->mapWithKeys(function ($item) {
            return [$item->id => app()->isLocale('bn') ? ($item->name_bn ?: $item->name) : ($item->name ?: $item->name_bn)];
        });
        
        $upazilaNames = Upazila::whereIn('id', $results->pluck('upazila_id'))->get(['id', 'name', 'name_bn'])->mapWithKeys(function ($item) {
            return [$item->id => app()->isLocale('bn') ? ($item->name_bn ?: $item->name) : ($item->name ?: $item->name_bn)];
        });
        
        $zillaNames = Zilla::whereIn('id', $results->pluck('zilla_id'))->get(['id', 'name', 'name_bn'])->mapWithKeys(function ($item) {
            return [$item->id => app()->isLocale('bn') ? ($item->name_bn ?: $item->name) : ($item->name ?: $item->name_bn)];
        });

        return [
            'results' => $results,
            'projectNames' => $projectNames,
            'unionNames' => $unionNames,
            'upazilaNames' => $upazilaNames,
            'zillaNames' => $zillaNames,
            'totalsByUnit' => $totalsByUnit,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'per_page' => $pageSize,
            ]
        ];
    }

    /**
     * Get duplicate allocations data for the selected economic year or all years.
     */
    private function getDuplicateAllocationsData($selectedYear, $search, $pageSize, $currentPage)
    {
        // Get organizations with multiple approved applications
        $query = DB::table('relief_applications as ra')
            ->join('projects as p', 'ra.project_id', '=', 'p.id')
            ->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
            ->join('economic_years as ey', 'p.economic_year_id', '=', 'ey.id')
            ->where('ra.status', 'approved')
            ->whereNotNull('ra.organization_name')
            ->where('ra.organization_name', '!=', '');
        
        // Filter by economic year if specified
        if ($selectedYear) {
            $query->where('p.economic_year_id', $selectedYear->id);
        }
        
        $query->selectRaw('
                ra.organization_name,
                COUNT(DISTINCT ra.id) as allocations,
                SUM(ra.approved_amount) as total_approved,
                GROUP_CONCAT(DISTINCT ey.name ORDER BY ey.start_date DESC) as economic_years,
                GROUP_CONCAT(DISTINCT ey.id ORDER BY ey.start_date DESC) as economic_year_ids
            ')
            ->groupBy('ra.organization_name')
            ->having('allocations', '>', 1)
            ->orderByDesc('allocations');

        // Apply search filter
        if ($search) {
            $query->where('ra.organization_name', 'LIKE', "%{$search}%");
        }

        $totalItems = $query->count();
        $totalPages = ceil($totalItems / $pageSize);
        
        $results = $query->skip(($currentPage - 1) * $pageSize)
            ->take($pageSize)
            ->get();

        // Calculate totals by unit for duplicates (based on approved amounts matching filters)
        $totalsByUnitQuery = DB::table('relief_applications as ra')
            ->leftJoin('projects as p', 'ra.project_id', '=', 'p.id')
            ->leftJoin('relief_types as rt', 'p.relief_type_id', '=', 'rt.id')
            ->when($selectedYear, function ($q) use ($selectedYear) {
                return $q->where('p.economic_year_id', $selectedYear->id);
            })
            ->where('ra.status', 'approved')
            ->select([
                DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
                DB::raw('SUM(ra.approved_amount) as total_amount')
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
            'results' => $results,
            'totalsByUnit' => $totalsByUnit,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'per_page' => $pageSize,
            ]
        ];
    }

    /**
     * Get project allocations data with pagination and search.
     */
    private function getProjectAllocationsData($selectedYear, $search, $pageSize, $currentPage)
    {
        $baseQuery = Project::with(['reliefType', 'economicYear'])
            ->when($selectedYear, function ($q) use ($selectedYear) {
                return $q->where('economic_year_id', $selectedYear->id);
            })
            ->where('allocated_amount', '>', 0);

        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('reliefType', function ($reliefQuery) use ($search) {
                      $reliefQuery->where('name', 'LIKE', "%{$search}%")
                                  ->orWhere('name_bn', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Calculate totals by unit based on allocated_amount
        $totalsByUnitQuery = (clone $baseQuery)->leftJoin('relief_types as rt', 'projects.relief_type_id', '=', 'rt.id')
            ->select([
                DB::raw('COALESCE(rt.unit_bn, rt.unit, "") as unit'),
                DB::raw('SUM(projects.allocated_amount) as total_amount')
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
        $totalPages = ceil($totalItems / $pageSize);
        $offset = ($currentPage - 1) * $pageSize;

        $results = $query->offset($offset)->limit($pageSize)->get();

        // Get application counts for each project
        $projectIds = $results->pluck('id');
        $applicationCounts = ReliefApplication::whereIn('project_id', $projectIds)
            ->where('status', 'approved')
            ->selectRaw('project_id, COUNT(*) as application_count')
            ->groupBy('project_id')
            ->pluck('application_count', 'project_id');

        // Add application count to each project
        $results = $results->map(function ($project) use ($applicationCounts) {
            $project->application_count = $applicationCounts[$project->id] ?? 0;
            return $project;
        });

        return [
            'results' => $results,
            'totalsByUnit' => $totalsByUnit,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'per_page' => $pageSize,
            ]
        ];
    }
}
