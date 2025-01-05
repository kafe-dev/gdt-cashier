<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function index()
    {
        return view('customer.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  Customer ID to show the detail
     */
    public function show(int|string $id): View
    {
        return view('customer.show');
    }

    /**
     * Action `create`.
     *
     * @param  Request  $request  Illuminate Request object
     */
    public function create(Request $request): View|RedirectResponse
    {
        return view('customer.create');
    }

    /**
     * Action `update`.
     *
     * @param  int|string  $id  Customer ID to update
     * @param  Request  $request  Illuminate Request object
     */
    public function update(int|string $id, Request $request): View|RedirectResponse
    {
        return view('customer.update');
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  Customer ID to delete
     * @param  Request  $request  Illuminate Request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        //
    }
}
