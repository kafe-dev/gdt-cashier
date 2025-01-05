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
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('user.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  User ID
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
     * @param  Request  $request  Illuminate request object
     */
    public function create(Request $request): View|RedirectResponse
    {
        return view('user.create');
    }

    /**
     * Action `update`.
     *
     * @param  int|string  $id  User ID
     * @param  Request  $request  Illuminate request object
     */
    public function update(int|string $id, Request $request): View|RedirectResponse
    {
        return view('user.update', [
            'user' => $this->getUser($id),
        ]);
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  User ID
     * @param  Request  $request  Illuminate request object
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
     *
     * @param  int|string  $id  User ID
     */
    private function getUser(int|string $id): UserModel
    {
        return $this->userModel->query()->findOrFail($id);
    }
}
