<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class Store.
 *
 * This controller is responsible for managing store-related operations.
 */
class Store extends BaseController
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
     * Action `create`.
     */
    public function create(): View
    {
        return view('store.create');
    }

    /**
     * Action `update`.
     */
    public function update(int|string $id): View
    {
        return view('store.update');
    }

    /**
     * Action `update`.
     */
    public function delete(int|string $id): RedirectResponse
    {
        //
    }

}
