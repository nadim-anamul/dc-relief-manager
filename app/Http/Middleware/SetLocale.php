<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
	public function handle(Request $request, Closure $next): Response
	{
		$lang = $request->get('lang')
			?? ($request->user()?->preferred_locale ?? null)
			?? session('app_locale');

		if ($lang && in_array($lang, ['bn', 'en'])) {
			app()->setLocale($lang);
			session(['app_locale' => $lang]);
		}

		return $next($request);
	}
}


