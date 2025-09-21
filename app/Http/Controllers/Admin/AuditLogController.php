<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuditLogController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): View
	{
		$query = AuditLog::with(['user', 'auditable'])
			->orderBy('created_at', 'desc');

		// Filter by event type
		$event = $request->query('event');
		if ($event && $event !== 'all') {
			$query->byEvent($event);
		}

		// Filter by user
		$userId = $request->query('user_id');
		if ($userId) {
			$query->byUser($userId);
		}

		// Filter by auditable type
		$auditableType = $request->query('auditable_type');
		if ($auditableType) {
			$query->byAuditableType($auditableType);
		}

		// Filter by date range
		$startDate = $request->query('start_date');
		$endDate = $request->query('end_date');
		if ($startDate && $endDate) {
			$query->byDateRange($startDate, $endDate);
		}

		$auditLogs = $query->paginate(20);

		// Get filter options
		$events = ['created', 'updated', 'deleted', 'restored'];
		$auditableTypes = AuditLog::distinct('auditable_type')->pluck('auditable_type')->map(function ($type) {
			$parts = explode('\\', $type);
			return end($parts);
		})->sort()->values();

		return view('admin.audit-logs.index', compact(
			'auditLogs',
			'events',
			'auditableTypes',
			'event',
			'userId',
			'auditableType',
			'startDate',
			'endDate'
		));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(AuditLog $auditLog): View
	{
		$auditLog->load(['user', 'auditable']);
		return view('admin.audit-logs.show', compact('auditLog'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(AuditLog $auditLog): RedirectResponse
	{
		$auditLog->delete();

		return redirect()->route('admin.audit-logs.index')
			->with('success', 'Audit log deleted successfully.');
	}

	/**
	 * Clear old audit logs.
	 */
	public function clear(Request $request): RedirectResponse
	{
		$request->validate([
			'days' => 'required|integer|min:1|max:365',
		]);

		$days = $request->input('days');
		$deletedCount = AuditLog::where('created_at', '<', now()->subDays($days))->delete();

		return redirect()->route('admin.audit-logs.index')
			->with('success', "Cleared {$deletedCount} audit logs older than {$days} days.");
	}
}