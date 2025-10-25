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
		
		// Get users who have made changes
		$users = \App\Models\User::whereHas('auditLogs')->orderBy('name')->get();

		// Get statistics
		$todayLogs = AuditLog::whereDate('created_at', today())->count();
		$todayUpdates = AuditLog::whereDate('created_at', today())->where('event', 'updated')->count();
		$todayDeletes = AuditLog::whereDate('created_at', today())->where('event', 'deleted')->count();

		return view('admin.audit-logs.index', compact(
			'auditLogs',
			'events',
			'auditableTypes',
			'users',
			'event',
			'userId',
			'auditableType',
			'startDate',
			'endDate',
			'todayLogs',
			'todayUpdates',
			'todayDeletes'
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
	 * Export audit logs to CSV.
	 */
	public function export(Request $request)
	{
		$query = AuditLog::with(['user', 'auditable'])
			->orderBy('created_at', 'desc');

		// Apply same filters as index method
		$event = $request->query('event');
		if ($event && $event !== 'all') {
			$query->byEvent($event);
		}

		$userId = $request->query('user_id');
		if ($userId) {
			$query->byUser($userId);
		}

		$auditableType = $request->query('auditable_type');
		if ($auditableType) {
			$query->byAuditableType($auditableType);
		}

		$startDate = $request->query('start_date');
		$endDate = $request->query('end_date');
		if ($startDate && $endDate) {
			$query->byDateRange($startDate, $endDate);
		}

		$auditLogs = $query->get();

		$filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

		$headers = [
			'Content-Type' => 'text/csv',
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
		];

		$callback = function() use ($auditLogs) {
			$file = fopen('php://output', 'w');
			
			// Add CSV headers
			fputcsv($file, [
				'ID',
				'Event',
				'Model Type',
				'Model ID',
				'User',
				'IP Address',
				'User Agent',
				'URL',
				'Created At',
				'Changed Fields',
				'Remarks'
			]);

			// Add data rows
			foreach ($auditLogs as $log) {
				fputcsv($file, [
					$log->id,
					$log->event_display,
					$log->auditable_type,
					$log->auditable_id,
					$log->user->name ?? 'System',
					$log->ip_address ?? '-',
					$log->user_agent ?? '-',
					$log->url ?? '-',
					$log->created_at->format('Y-m-d H:i:s'),
					$log->changed_fields ? implode(', ', $log->changed_fields) : '-',
					$log->remarks ?? '-'
				]);
			}

			fclose($file);
		};

		return response()->stream($callback, 200, $headers);
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