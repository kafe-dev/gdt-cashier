<?php

// Dashboard > Manage Orders
use App\Models\Order;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('manage-order', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage order', route('app.order.index'));
});

// Dashboard > Manage Orders > Show order
Breadcrumbs::for('show-order', function (BreadcrumbTrail $trail, Order $order) {
    $trail->parent('manage-order');
    $trail->push('Show Order}', route('app.order.show', ['id' => $order->id]));
});

// Dashboard > Manage Orders > Create order
Breadcrumbs::for('create-order', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-order');
    $trail->push('Create Order', route('app.order.create'));
});

// Dashboard > Manage Orders > Update order
Breadcrumbs::for('update-order', function (BreadcrumbTrail $trail, Order $order) {
    $trail->parent('manage-order');
    $trail->push('Update Order', route('app.order.update', ['id' => $order->id]));
});
