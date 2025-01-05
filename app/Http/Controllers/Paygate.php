<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class Paygate.
 *
 * This controller is responsible for managing paygate-related operations.
 */
class Paygate extends BaseController
{

    /**
     * Action `index`.
     */
    public function index(): View
    {
        return view('paygate.index');
    }

    /**
     * Action `show`.
     */
    public function show(int|string $id): View
    {
        return view('paygate.show');
    }

    /**
     * Action `create`.
     */
    public function create(): View
    {
        return view('paygate.create');
    }

    /**
     * Action `update`.
     */
    public function update(int|string $id): View
    {
        return view('paygate.update');
    }

    /**
     * Action `update`.
     */
    public function delete(int|string $id): RedirectResponse
    {
        //
    }

}
