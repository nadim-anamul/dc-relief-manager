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
     * Display Project × Upazila × Union Distribution page.
     */
    public function projectUpazilaUnion(Request $request): View
    {
        $years = EconomicYear::orderByDesc('start_date')->get();
        $selectedYear = $this->resolveSelectedYear($request, $years);
        
        $zillas = Zilla::orderBy('name')->get(['id', 'name']);
        $selectedZillaId = $this->resolveSelectedZilla($request, $zillas);
        
        $selectedUpazilaId = $request->integer('upazila_id');
        $selectedProjectId = $request->integer('project_id');
        $pageSize = 20;
        $currentPage = $request->integer('page', 1);

        $data = $this->getProjectUpazilaUnionData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId, $pageSize, $currentPage);
        $chartData = $this->getProjectUpazilaUnionChartData($data['distribution']);
        $unionChartData = $this->getUnionReliefChartData($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId);
        $projectBudgetBreakdown = $this->getProjectBudgetBreakdown($selectedYear, $selectedZillaId, $selectedUpazilaId, $selectedProjectId);

        $upazilas = $selectedZillaId ? Upazila::where('zilla_id', $selectedZillaId)->orderBy('name')->get(['id', 'name']) : collect();
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
        return $years->firstWhere('is_current', true) ?? $years->first();
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
        
        // If only one zilla exists, set it as default
        if ($zillas->count() === 1) {
            return $zillas->first()->id;
        }
        
        return null;
    }
}
