<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function index()
    {
        return view('paygate.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  Paygate ID to be shown
     */
    public function show(int|string $id): View
    {
        return view('paygate.show');
    }

    /**
     * Action `create`.
     *
     * @param  Request  $request  Illuminate request object
     */
    public function create(Request $request): View|RedirectResponse
    {
        return view('paygate.create');
    }

    /**
     * Action `update`.
     *
     * @param  int|string  $id  Paygate ID to be updated
     * @param  Request  $request  Illuminate request object
     */
    public function update(int|string $id, Request $request): View|RedirectResponse
    {
        return view('paygate.update');
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  Paygate ID to be deleted
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        //
    }
}
