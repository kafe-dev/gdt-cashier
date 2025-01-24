<?php

use App\Models\Store;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard > Manage Stores
Breadcrumbs::for('manage-store', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Stores', route('app.store.index'));
});

// Dashboard > Manage Stores > Show Store
Breadcrumbs::for('show-store', function (BreadcrumbTrail $trail, Store $store) {
    $trail->parent('manage-store');
    $trail->push('Show Store', route('app.store.show', ['id' => $store->id]));
});

// Dashboard > Manage Stores > Create Store
Breadcrumbs::for('create-store', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-store');
    $trail->push('Create Store', route('app.store.create'));
});

// Dashboard > Manage Stores > Update Store
Breadcrumbs::for('update-store', function (BreadcrumbTrail $trail, Store $store) {
    $trail->parent('manage-store');
    $trail->push('Update Stores', route('app.store.update', ['id' => $store->id]));
});
