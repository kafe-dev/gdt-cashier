<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function index()
    {
        return view('mail-box.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  The ID of the mail-box to be shown
     */
    public function show(int|string $id): View
    {
        return view('mail-box.show');
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  ID of the mail-box to be deleted
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        //
    }
}
