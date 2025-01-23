<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class Security.
 *
 * This controller handles security-related operations.
 */
class Security extends BaseController
{
    /**
     * Action `login`.
     *
     * @param Request $request Illuminate request object
     * @return View|RedirectResponse
     */
    public function login(Request $request): View|RedirectResponse
    {
        if ($request->getMethod() === "POST") {
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                flash()->success("Welcome back!");
                return redirect()->intended(route('app.home.index', absolute: false));
            }

            flash()->error("The provided credentials do not match our records.");
            return back();
        }

        return view('security.login');
    }

    /**
     * Action `logout`.
     *
     * @param  Request  $request  Illuminate request object
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('app.security.login');
    }
}
