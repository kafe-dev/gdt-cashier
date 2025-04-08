<?php

use App\Models\Permission;
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard > Manage Users
Breadcrumbs::for('manage-user', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage User', route('app.user.index'));
});

// Dashboard > Manage Users > Show User
Breadcrumbs::for('show-user', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('manage-user');
    $trail->push('Show User', route('app.user.show', ['id' => $user->id]));
});

// Dashboard > Manage Users > Create User
Breadcrumbs::for('create-user', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-user');
    $trail->push('Create User', route('app.user.create'));
});

// Dashboard > Manage Users > Update User
Breadcrumbs::for('update-user', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('manage-user');
    $trail->push('Update User', route('app.user.edit', ['id' => $user->id]));
});

// Dashboard > Change Password
Breadcrumbs::for('change-password', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Change Password', route('app.user.changePassword'));
});

//Dashboard > Manage Users > Manage Users' Role
Breadcrumbs::for('manage-role', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-user');
    $trail->push('Manage Users Role', route('app.user.roleManage.index'));
});

//Dashboard > Manage Users > > Manage Users' Role > Update User's Role
Breadcrumbs::for('update-role', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('manage-role');
    $trail->push('Update User Role', route('app.user.roleManage.edit', ['id' => $user->id]));
});

//Dashboard > Manage Users > Manage Roles' Permission
Breadcrumbs::for('manage-permission', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-user');
    $trail->push('Manage Roles Permission', route('app.user.permission.index'));
});

// Dashboard > Manage Users > Manage Roles' Permission > Create Role's Permission
Breadcrumbs::for('create-permission', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-permission');
    $trail->push('Create Role Permission', route('app.user.permission.create'));
});

//Dashboard > Manage Users > Manage Roles' Permission > Update Role's Permission
Breadcrumbs::for('update-permission', function (BreadcrumbTrail $trail, Permission $permission) {
    $trail->parent('manage-permission');
    $trail->push('Update Role Permission', route('app.user.permission.edit', ['id' => $permission->id]));
});

Breadcrumbs::for('manage-role-hierarchy', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-permission');
    $trail->push('Manage Role Hierarchy', route('app.user.role.hierarchy.index'));
});

// Dashboard > Manage Users > Role Hierarchy > Create Hierarchy
Breadcrumbs::for('create-role-hierarchy', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-role-hierarchy');
    $trail->push('Create Role Hierarchy', route('app.user.role.hierarchy.create'));
});

// Dashboard > Manage Users > Role Hierarchy > Update Hierarchy
Breadcrumbs::for('update-role-hierarchy', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('manage-role-hierarchy');
    $trail->push('Update Role Hierarchy', route('app.user.role.hierarchy.edit', ['id' => $id]));
});
