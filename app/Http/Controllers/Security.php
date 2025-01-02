<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
     * Renders the login view.
     */
    public function login(): View
    {
        return view('security.login');
    }
}
