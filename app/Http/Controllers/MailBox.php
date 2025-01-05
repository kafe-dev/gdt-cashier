<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class MailBox.
 *
 * This controller is responsible for managing mailbox-related operations.
 */
class MailBox extends BaseController
{

    /**
     * Action `index`.
     */
    public function index(): View
    {
        return view('mail-box.index');
    }

    /**
     * Action `show`.
     */
    public function show(int|string $id): View
    {
        return view('mail-box.show');
    }

    /**
     * Action `update`.
     */
    public function delete(int|string $id): RedirectResponse
    {
        //
    }

}
