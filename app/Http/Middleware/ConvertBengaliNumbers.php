<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertBengaliNumbers
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only process POST and PUT requests with form data
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH']) && $request->isMethod('post')) {
            $this->convertBengaliNumbersInInput($request);
        }

        return $next($request);
    }

    /**
     * Convert Bengali numbers to English numbers in request input.
     */
    private function convertBengaliNumbersInInput(Request $request): void
    {
        $input = $request->all();
        $convertedInput = $this->recursivelyConvertNumbers($input);
        
        // Replace the request input with converted values
        $request->replace($convertedInput);
    }

    /**
     * Recursively convert Bengali numbers in array data.
     */
    private function recursivelyConvertNumbers($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'recursivelyConvertNumbers'], $data);
        }

        if (is_string($data)) {
            // Check if the string contains Bengali digits
            if (preg_match('/[০-৯]/', $data)) {
                // Convert Bengali numbers to English numbers
                return bn_to_en_number($data);
            }
        }

        return $data;
    }
}
