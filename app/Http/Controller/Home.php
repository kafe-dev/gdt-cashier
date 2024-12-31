<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Illuminate\View\View;

/**
 * Class Home.
 *
 * This is the default controller for the application.
 */
class Home extends BaseController
{
    /**
     * Action `index`.
     *
     * Renders the app homepage.
     */
    public function index(): View
    {
        return view('home.index');
    }
}
