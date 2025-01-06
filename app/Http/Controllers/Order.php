<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class Order.
 *
 * This controller is responsible for managing order-related operations.
 */
class Order extends BaseController
{
    /**
     * Action `index`.
     */
    public function index()
    {
        return view('order.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  Order ID to show
     */
    public function show(int|string $id): View
    {
        return view('order.show');
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  Order ID to delete
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        //
    }
}
