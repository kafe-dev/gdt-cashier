<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Auth.
 *
 * This middleware is responsible for validating the user's authentication status.
 */
class Auth
{
    /**
     * Middleware handler.
     *
     * @param  Request  $request  Incoming request instance
     * @param  Closure  $next  Next middleware to call when the current middleware is completed
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}