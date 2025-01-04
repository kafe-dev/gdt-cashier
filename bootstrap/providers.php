<?php

use Maatwebsite\Excel\ExcelServiceProvider;
use Mailjet\LaravelMailjet\MailjetServiceProvider;
use Mailjet\LaravelMailjet\Providers\ContactsServiceProvider;
use Yajra\DataTables\ButtonsServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;
use Yajra\DataTables\FractalServiceProvider;
use Yajra\DataTables\HtmlServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    MailjetServiceProvider::class,
    ContactsServiceProvider::class,
    DataTablesServiceProvider::class,
    ButtonsServiceProvider::class,
    HtmlServiceProvider::class,
    ExcelServiceProvider::class,
    FractalServiceProvider::class,
];
