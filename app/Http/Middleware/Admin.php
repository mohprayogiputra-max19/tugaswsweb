<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Admin extends RoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        return parent::handle($request, $next, 'admin');
    }

    public function terminate(Request $request, Response $response): void
    {
        Log::info('Request selesai', [
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
        ]);
    }
}
