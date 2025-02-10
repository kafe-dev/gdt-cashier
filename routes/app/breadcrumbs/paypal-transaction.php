<?php

// Dashboard > Manage Paygates
use App\Models\PaypalTransaction;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('manage-paypal-transactions', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Transaction', route('app.paypal-transaction.index'));
});

Breadcrumbs::for('show-paypal-transaction', function (BreadcrumbTrail $trail, PaypalTransaction $transaction) {
    $trail->parent('manage-paypal-transactions');
    $trail->push('Show Paypal Transaction', route('app.paypal-transaction.show', $transaction->id));
});
