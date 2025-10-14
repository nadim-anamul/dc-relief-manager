<?php

namespace App\Http\Middleware;

use App\Models\EconomicYear;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UpdateCurrentEconomicYearMiddleware
{
    private const CACHE_KEY = 'economic_year_last_update';
    private const CACHE_TTL = 3600; // 1 hour

    public function handle(Request $request, Closure $next): Response
    {
        // Only update if last check was more than 1 hour ago
        if ($this->shouldUpdateCurrentYear()) {
            try {
                EconomicYear::updateCurrentYear();
                $this->markAsUpdated();
            } catch (\Exception $e) {
                // Log error but don't break the request
                \Log::error('Failed to update current economic year: ' . $e->getMessage());
            }
        }

        return $next($request);
    }

    private function shouldUpdateCurrentYear(): bool
    {
        // Check if auto-update is enabled
        if (!config('economic-year.auto_update', true)) {
            return false;
        }

        // Check if we've updated recently
        $lastUpdate = Cache::get(self::CACHE_KEY);
        if ($lastUpdate && (time() - $lastUpdate) < self::CACHE_TTL) {
            return false;
        }

        return true;
    }

    private function markAsUpdated(): void
    {
        Cache::put(self::CACHE_KEY, time(), self::CACHE_TTL);
    }
}
