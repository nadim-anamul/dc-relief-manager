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

		// Filter by date range if provided
		if ($request->filled('start_date')) {
			$query->where('start_date', '>=', $request->start_date);
		}

		if ($request->filled('end_date')) {
			$query->where('end_date', '<=', $request->end_date);
		}

		$projects = $query->orderBy('start_date', 'desc')->paginate(15);
		$economicYears = EconomicYear::where('is_active', true)->orderBy('start_date', 'desc')->get();
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();

		return view('admin.projects.index', compact('projects', 'economicYears', 'reliefTypes'));
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