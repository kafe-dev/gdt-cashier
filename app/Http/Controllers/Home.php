<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\BaseController;
use Illuminate\View\View;

/**
 * Class Home.
 *
 * This is the default controller of this application.
 */
class Home extends BaseController
{
    /**
     * Index action.
     *
     * Renders the homepage view.
     *
     * @return View
     */
    public function index(): View
    {
        return view('home.index');
    }

}
