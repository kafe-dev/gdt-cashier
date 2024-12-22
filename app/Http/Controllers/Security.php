<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\BaseController;
use Illuminate\View\View;

/**
 * Class Security.
 *
 * This controller is used to handle all security requests for this app.
 */
class Security extends BaseController
{
    /**
     * Action login.
     *
     * This action displays the login page and handles the login request.
     *
     * @return View
     */
    public function login(): View
    {
        return view('security.login');
    }

}
