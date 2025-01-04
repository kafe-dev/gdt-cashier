<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DataTables\UserDataTable;

/**
 * Class User.
 *
 * This controller is responsible for managing user-related operations.
 */
class User extends BaseController
{
    /**
     * Action `index`.
     *
     * This action renders the user index page.
     */
    public function index(UserDataTable $dataTable): mixed
    {
        return $dataTable->render('user.index');
    }
}
