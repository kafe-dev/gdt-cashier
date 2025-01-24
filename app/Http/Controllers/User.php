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
     * @param int|string $id User ID
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
     * @param Request $request Illuminate request object
     */
    public function create(Request $request): View|RedirectResponse
    {
        return view('user.create');
    }

    /**
     * Action `edit`.
     *
     * Show the form to update an existing user.
     *
     * @param int|string $id User ID
     */
    public function edit(int|string $id): View|RedirectResponse
    {
        return view('user.update', [
            'user' => $this->getUser($id),
        ]);
    }

    /**
     * Action `delete`.
     *
     * @param int|string $id User ID
     * @param Request $request Illuminate request object
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
     * Action 'update'.
     *
     * Store the updated user information in the database.
     *
     * @param Request $request Illuminate request object
     * @param int|string $id User ID
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8',
            ]);

            $user = $this->getUser($id);

            $user->update([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => $request->input('password') ? bcrypt($request->input('password')) : $user->password,
                'role' => $request->input('role'),
            ]);

            flash()->success('User updated successfully.');
            return redirect()->route('app.user.index');
        } catch (\Exception $e) {
            flash()->error('Email or Username already exists.');
            return redirect()->route('app.user.edit', $id);
        }
    }

    /**
     * Action `store`.
     *
     * Handles the storage of a new user.
     *
     * @param Request $request Illuminate request object
     *
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);
            $role = $request->input('role');

            if ($role == '') $role = UserModel::ROLE_USER;
            $this->userModel->create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role' => $role,
                'registration_ip' => $request->ip(),
            ]);

            flash()->success('User created successfully.');
        } catch (\Exception $e) {
            flash()->error('Email or Username already exists.');
            return redirect()->route('app.user.create');
        }

        return redirect()->route('app.user.index');
    }

    /**
     * Returns the specific user based on the given ID.
     *
     * @param int|string $id User ID
     */
    private function getUser(int|string $id): UserModel
    {
        return $this->userModel->query()->findOrFail($id);
    }

    /**
     * Action `changeStatus`.
     *
     * Change the status of the user to active or inactive.
     *
     * @param int|string $id User ID
     * @param Request $request Illuminate request object
     */
    public function changeStatus(Request $request, int|string $id): RedirectResponse
    {
        try {
            $user = $this->getUser($id);

            if ($user->status == UserModel::STATUS_ACTIVE) {
                $user->update([
                    'blocked_at' => now(),
                    'status' => UserModel::STATUS_INACTIVE,
                ]);
            } else {
                $user->update([
                    'status' => UserModel::STATUS_ACTIVE,
                    'blocked_at' => null,
                ]);
            }
            flash()->success('Change successfully.');
        } catch (\Exception $e) {
            flash()->error('Had an error while updating the status of the user.');
            return redirect()->route('app.user.index');
        }
        return redirect()->route('app.user.index');
    }
}
