<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class RequestLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $data = [
            'path'         => $request->getPathInfo(),
            'method'       => $request->getMethod(),
            'origin_ip'           => $request->ip(),
            'user_id' => $request->user() ? $request->user()->id : null,
            'request' => $request->except('password'),
            'response_status' => $response->status(),
            'response_content' => $this->getSanitizedResponseContent($response)
        ];

        Log::info('RequestLogger', $data);

        return $response;
    }

    private function getSanitizedResponseContent($response)
    {
        // Decode the JSON response
        $responseData = json_decode($response->getContent(), true);

        // Check if access_token or refresh_token exist in the response and remove them
        if (isset($responseData['access_token'])) {
            unset($responseData['access_token']);
        }
        if (isset($responseData['refresh_token'])) {
            unset($responseData['refresh_token']);
        }

        // Encode the modified response back to JSON
        return json_encode($responseData);
    }
}