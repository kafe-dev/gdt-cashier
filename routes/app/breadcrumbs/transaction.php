<?php

// Dashboard > Manage Paygates
use App\Models\Paygate;
use App\Models\Transaction;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('manage-transaction', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Transaction', route('app.transaction.index'));
});

// Dashboard > Manage Paygates > Show Paygate
Breadcrumbs::for('show-transaction', function (BreadcrumbTrail $trail, Transaction $transaction) {
    $trail->parent('manage-transaction');
    $trail->push('Show Transaction}', route('app.transaction.show', ['id' => $transaction->id]));
});

// Dashboard > Manage Transaction > Create Transaction
Breadcrumbs::for('create-transaction', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-transaction');
    $trail->push('Create Transaction', route('app.transaction.create'));
});

// Dashboard > Manage Transactions > Update Transaction
Breadcrumbs::for('update-transaction', function (BreadcrumbTrail $trail, Transaction $transaction) {
    $trail->parent('manage-transaction');
    $trail->push('Update Transaction', route('app.transaction.update', ['id' => $transaction->id]));
});
