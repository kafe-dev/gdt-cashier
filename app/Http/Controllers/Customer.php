<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class Customer.
 *
 * This controller is responsible for managing customer-related operations.
 */
class Customer extends BaseController
{

    /**
     * Action `index`.
     */
    public function index(): View
    {
        return view('customer.index');
    }

    /**
     * Action `show`.
     */
    public function show(int|string $id): View
    {
        return view('customer.show');
    }

    /**
     * Action `create`.
     */
    public function create(): View
    {
        return view('customer.create');
    }

    /**
     * Action `update`.
     */
    public function update(int|string $id): View
    {
        return view('customer.update');
    }

    /**
     * Action `update`.
     */
    public function delete(int|string $id): RedirectResponse
    {
        //
    }

}
