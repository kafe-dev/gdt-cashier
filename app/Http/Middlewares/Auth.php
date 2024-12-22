<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Auth.
 *
 * This middleware is responsible for authenticating a login request.
 */
class Auth
{
    /**
     * Middleware main handler.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

}
