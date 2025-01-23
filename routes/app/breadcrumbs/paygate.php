<?php

// Dashboard > Manage Paygates
use App\Models\Paygate;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('manage-paygate', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Paygate', route('app.paygate.index'));
});

// Dashboard > Manage Paygates > Show Paygate
Breadcrumbs::for('show-paygate', function (BreadcrumbTrail $trail, Paygate $paygate) {
    $trail->parent('manage-paygate');
    $trail->push('Show Paygate}', route('app.paygate.show', ['id' => $paygate->id]));
});

// Dashboard > Manage Paygates > Create Paygate
Breadcrumbs::for('create-paygate', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-paygate');
    $trail->push('Create Paygate', route('app.paygate.create'));
});

// Dashboard > Manage Paygates > Update Paygate
Breadcrumbs::for('update-paygate', function (BreadcrumbTrail $trail, Paygate $paygate) {
    $trail->parent('manage-paygate');
    $trail->push('Update Paygate', route('app.paygate.update', ['id' => $paygate->id]));
});
