<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		// Log the request if user is authenticated
		if (Auth::check()) {
			$this->logRequest($request);
		}

		$response = $next($request);

		// Log the response if needed
		if (Auth::check() && $this->shouldLogResponse($request, $response)) {
			$this->logResponse($request, $response);
		}

		return $response;
	}

	/**
	 * Log the incoming request.
	 */
	protected function logRequest(Request $request): void
	{
		$logData = [
			'user_id' => Auth::id(),
			'user_name' => Auth::user()->name,
			'method' => $request->method(),
			'url' => $request->fullUrl(),
			'ip_address' => $request->ip(),
			'user_agent' => $request->userAgent(),
			'route' => $request->route() ? $request->route()->getName() : null,
			'controller' => $request->route() ? $request->route()->getActionName() : null,
			'parameters' => $request->all(),
			'timestamp' => now()->toISOString(),
		];

		// Only log for specific routes/actions that modify data
		if ($this->shouldLogRequest($request)) {
			Log::channel('audit')->info('Request logged', $logData);
		}
	}

	/**
	 * Log the response.
	 */
	protected function logResponse(Request $request, Response $response): void
	{
		$logData = [
			'user_id' => Auth::id(),
			'user_name' => Auth::user()->name,
			'method' => $request->method(),
			'url' => $request->fullUrl(),
			'status_code' => $response->getStatusCode(),
			'response_time' => microtime(true) - LARAVEL_START,
			'timestamp' => now()->toISOString(),
		];

		Log::channel('audit')->info('Response logged', $logData);
	}

	/**
	 * Determine if the request should be logged.
	 */
	protected function shouldLogRequest(Request $request): bool
	{
		// Only log requests that modify data
		$methods = ['POST', 'PUT', 'PATCH', 'DELETE'];
		
		if (!in_array($request->method(), $methods)) {
			return false;
		}

		// Log specific routes
		$routesToLog = [
			'admin.zillas.*',
			'admin.upazilas.*',
			'admin.unions.*',
			'admin.wards.*',
			'admin.economic-years.*',
			'admin.relief-types.*',
			'admin.projects.*',
			'admin.organization-types.*',
			'admin.relief-applications.*',
			'relief-applications.*',
		];

		$routeName = $request->route() ? $request->route()->getName() : null;
		
		if (!$routeName) {
			return false;
		}

		foreach ($routesToLog as $pattern) {
			if (fnmatch($pattern, $routeName)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determine if the response should be logged.
	 */
	protected function shouldLogResponse(Request $request, Response $response): bool
	{
		// Only log responses for requests that were logged
		return $this->shouldLogRequest($request) && $response->getStatusCode() >= 200 && $response->getStatusCode() < 400;
	}
}