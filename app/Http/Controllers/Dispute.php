<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class Dispute.
 *
 * This controller is responsible for managing dispute-related operations.
 */
class Dispute extends BaseController
{
    /**
     * Action `index`.
     */
    public function index()
    {
        return view('store.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  Dispute ID to show
     */
    public function show(int|string $id): View
    {
        return view('store.show');
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  Dispute ID to delete
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        //
    }
}
