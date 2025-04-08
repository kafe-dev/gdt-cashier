<?php

namespace App\Http\Middlewares;

use App\Models\Permission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth as Authen;

class Role
{
    /**
     * Middleware handler for access control.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Authen::user();
        if (!$user) {
            flash()->error("You need to be logged in to access this page.");
            return redirect()->route('login');
        }

        if ($user->role === User::ROLE_ADMIN) {
            return $next($request);
        }

        $allowedRoutes = Permission::getAllowedRoutes($user->role);

        if (!in_array($request->route()?->getName(), $allowedRoutes, true)) {
            flash()->error("You don't have permission to access this page");
            return redirect()->route("app.home.index");
        }

        return $next($request);
    }
}
