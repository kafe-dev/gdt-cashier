<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
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
    public function index(): View
    {
        return view('store.index');
    }

    /**
     * Action `show`.
     */
    public function show(int|string $id): View
    {
        return view('store.show');
    }

    /**
     * Action `update`.
     */
    public function delete(int|string $id): RedirectResponse
    {
        //
    }

}
