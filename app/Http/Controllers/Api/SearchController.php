<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReliefApplication;
use App\Models\Project;
use App\Models\Zilla;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\OrganizationType;
use App\Models\ReliefType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
	/**
	 * Global search functionality
	 */
	public function search(Request $request): JsonResponse
	{
		$query = $request->get('q', '');
		
		if (strlen($query) < 2) {
			return response()->json([]);
		}

		$results = collect();

		// Search Relief Applications
		$applications = ReliefApplication::with(['zilla', 'upazila', 'union', 'reliefType', 'organizationType'])
			->where(function ($q) use ($query) {
				$q->where('organization_name', 'like', "%{$query}%")
				  ->orWhere('applicant_name', 'like', "%{$query}%")
				  ->orWhere('applicant_phone', 'like', "%{$query}%")
				  ->orWhere('details', 'like', "%{$query}%")
				  ->orWhereHas('zilla', function ($zq) use ($query) {
						$zq->where('name', 'like', "%{$query}%")
						   ->orWhere('name_bn', 'like', "%{$query}%");
				  })
				  ->orWhereHas('upazila', function ($uq) use ($query) {
						$uq->where('name', 'like', "%{$query}%")
						   ->orWhere('name_bn', 'like', "%{$query}%");
				  })
				  ->orWhereHas('union', function ($uq) use ($query) {
						$uq->where('name', 'like', "%{$query}%")
						   ->orWhere('name_bn', 'like', "%{$query}%");
				  });
			})
			->limit(5)
			->get()
			->map(function ($app) {
				return [
					'id' => 'app_' . $app->id,
					'title' => $app->application_type === 'individual' ? $app->applicant_name : $app->organization_name,
					'subtitle' => ($app->zilla?->name ?? '') . ' - ' . ($app->reliefType?->name ?? ''),
					'url' => route('admin.relief-applications.show', $app->id),
					'icon_bg' => $app->application_type === 'individual' ? 'bg-green-100 dark:bg-green-900' : 'bg-blue-100 dark:bg-blue-900',
					'icon_color' => $app->application_type === 'individual' ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400',
					'icon_path' => $app->application_type === 'individual' ? 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' : 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
					'type' => 'application'
				];
			});

		$results = $results->merge($applications);

		// Search Projects
		$projects = Project::with(['reliefType', 'economicYear'])
			->where('name', 'like', "%{$query}%")
			->orWhere('remarks', 'like', "%{$query}%")
			->orWhereHas('reliefType', function ($q) use ($query) {
				$q->where('name', 'like', "%{$query}%")
				  ->orWhere('name_bn', 'like', "%{$query}%");
			})
			->limit(3)
			->get()
			->map(function ($project) {
				return [
					'id' => 'proj_' . $project->id,
					'title' => $project->name,
					'subtitle' => ($project->reliefType?->name ?? '') . ' - ' . $project->formatted_allocated_amount,
					'url' => route('admin.projects.show', $project->id),
					'icon_bg' => 'bg-green-100 dark:bg-green-900',
					'icon_color' => 'text-green-600 dark:text-green-400',
					'icon_path' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
					'type' => 'project'
				];
			});

		$results = $results->merge($projects);

		// Search Administrative Areas
		$areas = collect();

		// Search Zillas
		$zillas = Zilla::where('name', 'like', "%{$query}%")
			->orWhere('name_bn', 'like', "%{$query}%")
			->limit(2)
			->get()
			->map(function ($zilla) {
				return [
					'id' => 'zilla_' . $zilla->id,
					'title' => $zilla->name,
					'subtitle' => __('District') . ' - ' . ($zilla->name_bn ?? ''),
					'url' => route('admin.relief-applications.index', ['zilla_id' => $zilla->id]),
					'icon_bg' => 'bg-purple-100 dark:bg-purple-900',
					'icon_color' => 'text-purple-600 dark:text-purple-400',
					'icon_path' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z',
					'type' => 'zilla'
				];
			});

		$areas = $areas->merge($zillas);

		// Search Upazilas
		$upazilas = Upazila::with('zilla')
			->where('name', 'like', "%{$query}%")
			->orWhere('name_bn', 'like', "%{$query}%")
			->orWhereHas('zilla', function ($q) use ($query) {
				$q->where('name', 'like', "%{$query}%")
				  ->orWhere('name_bn', 'like', "%{$query}%");
			})
			->limit(2)
			->get()
			->map(function ($upazila) {
				return [
					'id' => 'upazila_' . $upazila->id,
					'title' => $upazila->name,
					'subtitle' => __('Upazila') . ' - ' . ($upazila->zilla?->name ?? ''),
					'url' => route('admin.relief-applications.index', ['upazila_id' => $upazila->id]),
					'icon_bg' => 'bg-orange-100 dark:bg-orange-900',
					'icon_color' => 'text-orange-600 dark:text-orange-400',
					'icon_path' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
					'type' => 'upazila'
				];
			});

		$areas = $areas->merge($upazilas);

		// Search Unions
		$unions = Union::with(['upazila.zilla'])
			->where('name', 'like', "%{$query}%")
			->orWhere('name_bn', 'like', "%{$query}%")
			->limit(2)
			->get()
			->map(function ($union) {
				return [
					'id' => 'union_' . $union->id,
					'title' => $union->name,
					'subtitle' => __('Union') . ' - ' . ($union->upazila?->name ?? ''),
					'url' => route('admin.relief-applications.index', ['union_id' => $union->id]),
					'icon_bg' => 'bg-teal-100 dark:bg-teal-900',
					'icon_color' => 'text-teal-600 dark:text-teal-400',
					'icon_path' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
					'type' => 'union'
				];
			});

		$areas = $areas->merge($unions);

		$results = $results->merge($areas);

		// Search Organization Types
		$orgTypes = OrganizationType::where('name', 'like', "%{$query}%")
			->orWhere('description', 'like', "%{$query}%")
			->limit(2)
			->get()
			->map(function ($orgType) {
				return [
					'id' => 'org_' . $orgType->id,
					'title' => $orgType->name,
					'subtitle' => __('Organization Type'),
					'url' => route('admin.relief-applications.index', ['organization_type_id' => $orgType->id]),
					'icon_bg' => 'bg-indigo-100 dark:bg-indigo-900',
					'icon_color' => 'text-indigo-600 dark:text-indigo-400',
					'icon_path' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
					'type' => 'organization_type'
				];
			});

		$results = $results->merge($orgTypes);

		// Search Relief Types
		$reliefTypes = ReliefType::where('name', 'like', "%{$query}%")
			->orWhere('name_bn', 'like', "%{$query}%")
			->limit(2)
			->get()
			->map(function ($reliefType) {
				return [
					'id' => 'relief_' . $reliefType->id,
					'title' => $reliefType->name,
					'subtitle' => __('Relief Type') . ' - ' . ($reliefType->unit ?? ''),
					'url' => route('admin.relief-applications.index', ['relief_type_id' => $reliefType->id]),
					'icon_bg' => 'bg-red-100 dark:bg-red-900',
					'icon_color' => 'text-red-600 dark:text-red-400',
					'icon_path' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
					'type' => 'relief_type'
				];
			});

		$results = $results->merge($reliefTypes);

		// Limit total results and return
		return response()->json($results->take(10)->values());
	}

	/**
	 * Quick search suggestions
	 */
	public function suggestions(Request $request): JsonResponse
	{
		$query = $request->get('q', '');
		
		if (strlen($query) < 2) {
			return response()->json([]);
		}

		$suggestions = collect();

		// Get recent applications for suggestions
		$recentApps = ReliefApplication::with(['zilla', 'reliefType'])
			->where('organization_name', 'like', "%{$query}%")
			->orderBy('created_at', 'desc')
			->limit(3)
			->get()
			->map(function ($app) {
				return [
					'text' => $app->organization_name,
					'category' => __('Recent Applications'),
					'url' => route('admin.relief-applications.show', $app->id)
				];
			});

		$suggestions = $suggestions->merge($recentApps);

		// Get popular areas
		$popularAreas = Zilla::where('name', 'like', "%{$query}%")
			->orWhere('name_bn', 'like', "%{$query}%")
			->limit(2)
			->get()
			->map(function ($zilla) {
				return [
					'text' => $zilla->name,
					'category' => __('Districts'),
					'url' => route('admin.relief-applications.index', ['zilla_id' => $zilla->id])
				];
			});

		$suggestions = $suggestions->merge($popularAreas);

		return response()->json($suggestions->values());
	}
}
