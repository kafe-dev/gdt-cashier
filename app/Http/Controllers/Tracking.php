<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class Tracking.
 *
 * This controller is responsible for managing tracking-related operations.
 */
class Tracking extends BaseController
{

    /**
     * Action `index`.
     */
    public function index(): View
    {
        return view('tracking.index');
    }

    /**
     * Action `show`.
     */
    public function show(int|string $id): View
    {
        return view('tracking.show');
    }

    /**
     * Action `update`.
     */
    public function delete(int|string $id): RedirectResponse
    {
        //
    }

}
