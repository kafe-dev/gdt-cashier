<?php

use App\Models\OrderTracking;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard > Manage Order Tracking
Breadcrumbs::for('manage-order-tracking', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Order Tracking', route('app.tracking.index'));
});

// Dashboard > Manage Order Tracking > Show Order Tracking
Breadcrumbs::for('show-order-tracking', function (BreadcrumbTrail $trail, OrderTracking $orderTracking) {
    $trail->parent('manage-order-tracking');
    $trail->push('Show Order Tracking', route('app.tracking.show', ['id' => $orderTracking->id]));
});
