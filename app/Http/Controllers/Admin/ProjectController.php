<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\EconomicYear;
use App\Models\ReliefType;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): View
	{
		$query = Project::with(['economicYear', 'reliefType']);

		// Filter by economic year if provided
		if ($request->filled('economic_year_id')) {
			$query->where('economic_year_id', $request->economic_year_id);
		}

		// Filter by relief type if provided
		if ($request->filled('relief_type_id')) {
			$query->where('relief_type_id', $request->relief_type_id);
		}

		// Filter by status if provided
		if ($request->filled('status')) {
			switch ($request->status) {
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

		$projects = $query->orderBy('created_at', 'desc')->paginate(15);
		$economicYears = EconomicYear::where('is_active', true)->orderBy('start_date', 'desc')->get();
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();

		// Calculate summary statistics
		$stats = [
			'total' => Project::count(),
			'active' => Project::active()->count(),
			'completed' => Project::completed()->count(),
			'upcoming' => Project::upcoming()->count(),
		];

		// Calculate relief type allocation statistics with proper unit handling
		$reliefTypeStats = Project::query()
			->when($request->filled('economic_year_id'), function($q) use ($request) {
				$q->where('economic_year_id', $request->economic_year_id);
			})
			->when($request->filled('relief_type_id'), function($q) use ($request) {
				$q->where('relief_type_id', $request->relief_type_id);
			})
			->when($request->filled('status'), function($q) use ($request) {
				switch ($request->status) {
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
				
				// Format based on unit type
				if (in_array($unit, ['টাকা', 'Taka'])) {
					$stat->formatted_total = '৳' . $amount;
				} else {
					$stat->formatted_total = $amount . ' ' . $unit;
				}
				
				return $stat;
			});

		$stats['reliefTypeStats'] = $reliefTypeStats;

		return view('admin.projects.index', compact('projects', 'economicYears', 'reliefTypes', 'stats'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		$economicYears = EconomicYear::where('is_active', true)->orderBy('start_date', 'desc')->get();
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();

		return view('admin.projects.create', compact('economicYears', 'reliefTypes'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(ProjectRequest $request): RedirectResponse
	{
		$validated = $request->validated();
		
		// Set available_amount to allocated_amount when creating a new project
		$validated['available_amount'] = $validated['allocated_amount'];

		Project::create($validated);

		return redirect()->route('admin.projects.index')
			->with('success', 'Project created successfully.');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Project $project): View
	{
		$project->load(['economicYear', 'reliefType']);

		return view('admin.projects.show', compact('project'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Project $project): View
	{
		$economicYears = EconomicYear::where('is_active', true)->orderBy('start_date', 'desc')->get();
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();

		return view('admin.projects.edit', compact('project', 'economicYears', 'reliefTypes'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(ProjectRequest $request, Project $project): RedirectResponse
	{
		$validated = $request->validated();

		$project->update($validated);

		return redirect()->route('admin.projects.index')
			->with('success', 'Project updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Project $project): RedirectResponse
	{
		$project->delete();

		return redirect()->route('admin.projects.index')
			->with('success', 'Project deleted successfully.');
	}
}