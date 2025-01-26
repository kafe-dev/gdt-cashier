<?php

// Dashboard > Manage Paygates
use App\Models\Dispute;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('manage-dispute', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Dispute', route('app.dispute.index'));
});
// Dashboard > Manage Dispute > Show Dispute
Breadcrumbs::for('show-dispute', function (BreadcrumbTrail $trail, Dispute $dispute) {
    $trail->parent('manage-dispute');
    $trail->push('Show Dispute', route('app.dispute.show', ['id' => $dispute->id]));
});
