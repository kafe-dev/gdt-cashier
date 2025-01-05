<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User as UserModel;
use App\Services\DataTables\UserDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class User.
 *
 * This controller is responsible for managing user-related operations.
 */
class User extends BaseController
{
    /**
     * @var UserModel Instance of the User model
     */
    private UserModel $userModel;

    /**
     * Construct a new User controller instance.
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
     */
    public function show(int|string $id): View
    {
        return view('user.show', [
            'user' => $this->getUser($id),
        ]);
    }

    /**
     * Action `create`.
     */
    public function create(): View
    {
        return view('user.create');
    }

    /**
     * Action `update`.
     */
    public function update(int|string $id): View
    {
        return view('user.update', [
            'user' => $this->getUser($id),
        ]);
    }

    /**
     * Action `delete`.
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        if ($request->isMethod('POST')) {
            $user = $this->getUser($id);

            if ($user->delete()) {
                flash()->success('Deleted successfully.');
            }
        }

        return redirect()->route('app.user.index');
    }

    /**
     * Returns the specific user based on the given ID.
     */
    private function getUser(int|string $id): UserModel
    {
        return $this->userModel->query()->findOrFail($id);
    }
}
