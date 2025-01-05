<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function index()
    {
        return view('store.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  The store ID
     */
    public function show(int|string $id): View
    {
        return view('store.show');
    }

    /**
     * Action `create`.
     *
     * @param  Request  $request  Illuminate request object
     */
    public function create(Request $request): View|RedirectResponse
    {
        return view('store.create');
    }

    /**
     * Action `update`.
     *
     * @param  int|string  $id  The store ID
     * @param  Request  $request  Illuminate request object
     */
    public function update(int|string $id, Request $request): View|RedirectResponse
    {
        return view('store.update');
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  The store ID
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        //
    }
}
