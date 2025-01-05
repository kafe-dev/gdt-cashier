<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DataTables\UserDataTable;
use Illuminate\View\View;
use App\Models\User as UserModel;

/**
 * Class User.
 *
 * This controller is responsible for managing user-related operations.
 */
class User extends BaseController
{

    /**
     * @var UserModel $userModel Instance of the User model
     */
    private UserModel $userModel;

    /**
     * Construct a new User controller instance.
     *
     * @param  UserModel  $userModel
     */
    public function __construct(UserModel $userModel)
    {
        parent::__construct();

        $this->userModel = $userModel;
    }

    /**
     * Action `index`.
     *
     * This action renders the user index page.
     */
    public function index(UserDataTable $dataTable): mixed
    {
        return $dataTable->render('user.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id
     *
     * @return View
     */
    public function show(int|string $id): View
    {
        return view('user.show', [
            'user' => $this->getUser($id),
        ]);
    }

    /**
     * Action `create`.
     *
     * @return View
     */
    public function create(): View
    {
        return view('user.create');
    }

    /**
     * Action `update`.
     *
     * @param  int|string  $id
     *
     * @return View
     */
    public function update(int|string $id): View
    {
        return view('user.update', [
            'user' => $this->getUser($id),
        ]);
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id
     *
     * @return false|string
     */
    public function delete(int|string $id)
    {
        $user = $this->getUser($id);
        dd($user);
        return json_encode($user);
    }

    /**
     * Returns the specific user based on the given ID.
     *
     * @param  int|string  $id
     *
     * @return UserModel
     */
    private function getUser(int|string $id): UserModel
    {
        return $this->userModel->query()->findOrFail($id);
    }

}
