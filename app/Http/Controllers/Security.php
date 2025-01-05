<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * @param  Request  $request  Illuminate request object
     */
    public function login(Request $request): View|RedirectResponse
    {
        return view('security.login');
    }

    /**
     * Action `logout`.
     *
     * @param  Request  $request  Illuminate request object
     */
    public function logout(Request $request): RedirectResponse
    {
        //
    }
}
