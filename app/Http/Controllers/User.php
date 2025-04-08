<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RoleHierarchy;
use App\Models\User as UserModel;
use App\Services\DataTables\PermissionDatatable;
use App\Services\DataTables\RoleHierarchyDatatable;
use App\Services\DataTables\UserDataTable;
use App\Services\DataTables\UserRoleDataTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
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
     * @var Permission Instance of the Permission model
     */
    private Permission $permissionModel;

    /**
     * Construct a new User controller instance.
     */
    public function __construct(UserModel $userModel, Permission $permissionModel)
    {
        parent::__construct();

        $this->userModel = $userModel;
        $this->permissionModel = $permissionModel;
    }

    /**
     * Action `index`.
     */
    public function index(UserDataTable $dataTable)
    {
        $this->filterDateRange($dataTable);

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
     */
    public function create(): View|RedirectResponse
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
            $this->validate($request, false, true, $id);

            $user = $this->getUser($id);

            $user->update([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => $request->input('password') ? bcrypt($request->input('password')) : $user->password,
//                'role' => $request->input('role'),
            ]);

            flash()->success('User updated successfully.');

            return redirect()->route('app.user.index');
        } catch (ValidationException $e) {
            $this->showAllValidateErrors($e);

            return redirect()->route('app.user.edit', $id);
        } catch (Exception) {
            flash()->error('The email/password is already in use.');

            return redirect()->route('app.user.edit', $id);
        }
    }

    /**
     * Action `store`.
     *
     * Handles the storage of a new user.
     *
     * @param Request $request Illuminate request object
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->validate($request);

            $role = $request->input('role');

            if ($role === '') {
                $role = UserModel::ROLE_USER;
            }
            $this->userModel->create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role' => $role,
                'registration_ip' => $this->getRealClientIp(),
            ]);

            flash()->success('User created successfully.');
        } catch (ValidationException $e) {
            $this->showAllValidateErrors($e);

            return redirect()->route('app.user.create');
        } catch (Exception) {
            flash()->error('The email/password is already in use.');

            return redirect()->route('app.user.create');
        }

        return redirect()->route('app.user.index');
    }

    /**
     * Action 'index' for role manage page.
     */
    public function roleIndex(UserRoleDataTable $dataTable)
    {
        $this->filterDateRange($dataTable);

        return $dataTable->render('user.role.index');
    }

    /**
     * Action `edit`.
     *
     * Show the form to update an existing user's role.
     *
     * @param int|string $id
     * @return View
     */
    public function roleEdit(int|string $id): View
    {
        return view('user.role.edit', [
            'user' => $this->getUser($id),
        ]);
    }

    /**
     * Action 'update'.
     *
     * Store the updated user's role in the database.
     *
     * @param Request $request Illuminate request object
     * @param int|string $id User ID
     */
    public function roleUpdate(Request $request, int|string $id): RedirectResponse
    {
        try {
            $user = $this->getUser($id);

            $user->update([
                'role' => $request->input('role'),
                'updated_at' => Carbon::now(),
            ]);

            flash()->success('User updated successfully.');

            return redirect()->route('app.user.roleManage.index');
        }
        catch (Exception $e) {
            flash()->error($e->getMessage());

            return redirect()->route('app.user.roleManage.edit', $id);
        }
    }

    /**
     * Action 'index' for permission manage page.
     */
    public function permissionIndex(PermissionDatatable $datatable)
    {
        $roles = [];
        foreach (UserModel::ROLES as $key => $value) {
            $roles[$key] = RoleHierarchy::getAllowedRoles($key);
        }

        return $datatable->render('user.permission.index', [
            'roleHierarchies' => $roles,
        ]);
    }

    public function permissionCreate(): View
    {
        return view('user.permission.create');
    }

    public function permissionStore(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'role' => 'required|integer|unique:permissions,role',
            ], [
                'role.required' => 'The role field is required.',
                'role.integer' => 'The role must be an integer.',
                'role.unique' => 'This role already exists.',
            ]);

            Permission::create([
                'role' => $request->input('role'),
            ]);

            flash()->success('Permission created successfully.');

            return redirect()->route('app.user.permission.index');
        } catch (ValidationException $e) {
            $this->showAllValidateErrors($e);
            return redirect()->route('app.user.permission.create');
        } catch (Exception $e) {
            flash()->error('Error creating permission: ' . $e->getMessage());
            return redirect()->route('app.user.permission.create');
        }
    }

    /**
     * Action `edit`.
     *
     * Show the form to update an existing role's permission.
     *
     * @param int|string $id
     * @return View
     */
    public function permissionEdit(int|string $id): View
    {
        $routeNames = collect(Route::getRoutes())
            ->map(fn($route) => $route->getName())
            ->filter(fn($name) => !is_null($name) && str_starts_with($name, 'app.') && !str_starts_with($name, 'app.security') && !str_starts_with($name, 'app.permission.'))
            ->values();

        $routeNames = array_unique($routeNames->toArray());

        return view('user.permission.edit', [
            'permission' => $this->getPermission($id),
            'routeNames' => $routeNames,
            'routeAllowed' => Permission::getRoleAllowedRoutes($this->getPermission($id)->role) ?? ['1', '2'],
            'hierarchyAllowed' => Permission::getAllowedRoutesWithHierarchy($this->getPermission($id)->role) ?? ['1', '2'],
        ]);
    }

    /**
     * Action 'update'.
     *
     *  Store the updated role's permission in the database.
     *
     * @param Request $request
     * @param int|string $id
     * @return RedirectResponse
     */
    public function permissionUpdate(Request $request, int|string $id): RedirectResponse
    {
        try {
            $permission = $this->getPermission($id);

            $list = $request->all();
            unset($list['_token']);

            $json = [];
            foreach ($list as $value) {
                $json[] = $value;
            }

            $permission->update([
                'routes' => $json,
            ]);

            flash()->success('Permission updated successfully.');
            return redirect()->route('app.user.permission.index');
        } catch (ValidationException $e) {
            $this->showAllValidateErrors($e);
            return redirect()->route('app.user.permission.edit', $id);
        } catch (Exception $e) {
            flash()->error('Error updating permission: ' . $e->getMessage());

            return redirect()->route('app.user.permission.edit', $id);
        }
    }

    public function permissionDelete(int|string $id, Request $request): ?RedirectResponse
    {
        try {
            $permission = $this->getPermission($id);
            $permission->delete();
            flash()->success('Permission deleted successfully.');
            return redirect()->route('app.user.permission.index');
        } catch (Exception $e) {
            flash()->error('Error deleting permission: ' . $e->getMessage());
            return redirect()->route('app.user.permission.index');
        }
    }

    public function hierarchyIndex(RoleHierarchyDatatable $datatable)
    {
        return $datatable->render('user.hierarchy.index');
    }

    /**
     * Action `create` cho Role Hierarchy
     */
    public function hierarchyCreate(): View
    {
        $roles = UserModel::ROLES;
        return view('user.hierarchy.create', compact('roles'));
    }

    /**
     * Action `store` cho Role Hierarchy
     */
    public function hierarchyStore(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'parent_role' => 'required|integer',
                'child_role' => [
                    'required',
                    'integer',
                    'different:parent_role',
                    Rule::unique('role_hierarchies')->where(function ($query) use ($request) {
                        return $query->where('parent_role', $request->parent_role)
                            ->where('child_role', $request->child_role);
                    }),
                ],
            ], [
                'parent_role.required' => 'The parent role field is required.',
                'parent_role.integer' => 'The parent role must be an integer.',

                'child_role.required' => 'The child role field is required.',
                'child_role.integer' => 'The child role must be an integer.',
                'child_role.different' => 'The child role must be different from the parent role.',
                'child_role.unique' => 'This parent-child role combination already exists.',
            ]);

            $parentRole = $request->input('parent_role');
            $childRole = $request->input('child_role');

            RoleHierarchy::updateOrCreate(
                ['parent_role' => $parentRole, 'child_role' => $childRole],
                ['parent_role' => $parentRole, 'child_role' => $childRole]
            );
            flash()->success('Hierarchy created successfully.');
        } catch (ValidationException $e) {
            $this->showAllValidateErrors($e);
            return redirect()->route('app.user.role.hierarchy.create');
        } catch (\Exception $e) {
            flash()->error('Error creating hierarchy: ' . $e->getMessage());
            return redirect()->route('app.user.role.hierarchy.create');
        }

        return redirect()->route('app.user.role.hierarchy.index');
    }

    /**
     * Action `edit` cho Role Hierarchy
     */
    public function hierarchyEdit(int $id): View
    {
        $hierarchy = RoleHierarchy::findOrFail($id);
        return view('user.hierarchy.edit', compact('hierarchy'));
    }

    /**
     * Action `update` cho Role Hierarchy
     */
    public function hierarchyUpdate(Request $request, int $id): RedirectResponse
    {
        try {
            $request->validate([
                'parent_role' => 'required|integer',
                'child_role' => [
                    'required',
                    'integer',
                    'different:parent_role',
                    Rule::unique('role_hierarchies')->where(function ($query) use ($request) {
                        return $query->where('parent_role', $request->parent_role)
                            ->where('child_role', $request->child_role);
                    }),
                ],
            ], [
                'parent_role.required' => 'The parent role field is required.',
                'parent_role.integer' => 'The parent role must be an integer.',

                'child_role.required' => 'The child role field is required.',
                'child_role.integer' => 'The child role must be an integer.',
                'child_role.different' => 'The child role must be different from the parent role.',
                'child_role.unique' => 'This parent-child role combination already exists.',
            ]);


            $hierarchy = RoleHierarchy::findOrFail($id);
            $hierarchy->update([
                'parent_role' => $request->input('parent_role'),
                'child_role' => $request->input('child_role'),
            ]);
            flash()->success('Hierarchy updated successfully.');
        } catch (ValidationException $e) {
            $this->showAllValidateErrors($e);
            return redirect()->route('app.user.role.hierarchy.edit', $id);
        } catch (\Exception $e) {
            flash()->error('Error updating hierarchy: ' . $e->getMessage());
            return redirect()->route('app.user.role.hierarchy.edit', $id);
        }

        return redirect()->route('app.user.role.hierarchy.index');
    }

    /**
     * Action `delete` cho Role Hierarchy
     */
    public function hierarchyDelete(int $id): RedirectResponse
    {
        try {
            $hierarchy = RoleHierarchy::findOrFail($id);
            $hierarchy->delete();
            flash()->success('Hierarchy deleted successfully.');
        } catch (Exception $e) {
            flash()->error('Error deleting hierarchy: ' . $e->getMessage());
        }

        return redirect()->route('app.user.role.hierarchy.index');
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

    private function getPermission(int|string $id): Permission
    {
        return $this->permissionModel->query()->findOrFail($id);
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

            if ($user->status === UserModel::STATUS_ACTIVE) {
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
        } catch (Exception $e) {
            flash()->error('Had an error while updating the status of the user.');

            return redirect()->route('app.user.index');
        }

        return redirect()->route('app.user.index');
    }

    /**
     * Return real client ip
     *
     * @return mixed|string
     */
    private function getRealClientIp(): mixed
    {
        return $_SERVER['HTTP_CLIENT_IP']
            ?? $_SERVER["HTTP_CF_CONNECTING_IP"] # when behind cloudflare
            ?? $_SERVER['HTTP_X_FORWARDED']
            ?? $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['HTTP_FORWARDED']
            ?? $_SERVER['HTTP_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '0.0.0.0';
    }

    /**
     * Action `changePassword`.
     *
     * Change the password of the currently authenticated user.
     *
     * @param Request $request Illuminate request object
     *
     */
    public function changePassword(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            try {
                $this->validate($request, true);

                $user = Auth::user();

                if (!Hash::check($request->input('current_password'), $user->getAuthPassword())) {
                    flash()->error('Current password is incorrect.');
                    return redirect()->back();
                }

                $user->update([
                    'password' => Hash::make($request->input('new_password')),
                ]);

                flash()->success('Password changed successfully.');
                return redirect()->route('app.home.index');
            } catch (ValidationException $e) {
                $this->showAllValidateErrors($e);
                return redirect()->route('app.user.changePassword');
            } catch (Exception $e) {
                flash()->error('An error occurred while changing the password.');
                return redirect()->route('app.user.changePassword');
            }
        }
        return view('user.change_password');
    }

    /**
     * Show all validation errors.
     *
     * @param ValidationException $e Validation exception object
     */
    private function showAllValidateErrors(ValidationException $e): void
    {
        foreach ($e->errors() as $field => $messages) {
            foreach ($messages as $message) {
                flash()->error($message);
            }
        }
    }

    /**
     * Validate request data.
     *
     * @param Request $request Illuminate request object
     * @param bool $passwordChange Indicates whether a password change is requested.
     * @param bool $nullable Indicates whether the password field is nullable. Defaults to false (password is required).
     * @param int|string $id The user ID for uniqueness validation of the email field.
     */
    private function validate(Request $request, bool $passwordChange = false, bool $nullable = false, int|string $id = -1): void
    {
        if ($passwordChange === true) {
            $request->validate([
                'new_password' => [
                    'required',
                    'string',
                    Password::min(8)->max(255)
                        ->mixedCase() // 1 A-Z, 1 a-z
                        ->numbers()   // 1 number
                        ->symbols(),   // 1 in { @$!%*?& }
                ],
                'confirm_password' => 'required|same:new_password',
            ]);
        } else {
            $request->validate([
                'username' => 'required|string|max:50|regex:/^[a-zA-Z0-9_]*$/',
                'email' => 'required|email|unique:users,email' . ($id > -1 ? ','.$id : ''),
                'password' => [
                    ($nullable === true ? 'nullable' : 'required'),
                    'string',
                    Password::min(8)->max(255)
                        ->mixedCase() // 1 A-Z, 1 a-z
                        ->numbers()   // 1 number
                        ->symbols(),   // 1 in { @$!%*?& }
                ],
            ]);
        }
    }
}
