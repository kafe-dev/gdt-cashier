<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\CommandServiceProvider::class,
    Maatwebsite\Excel\ExcelServiceProvider::class,
    Mailjet\LaravelMailjet\MailjetServiceProvider::class,
    Mailjet\LaravelMailjet\Providers\ContactsServiceProvider::class,
    Yajra\DataTables\ButtonsServiceProvider::class,
    Yajra\DataTables\DataTablesServiceProvider::class,
    Yajra\DataTables\FractalServiceProvider::class,
    Yajra\DataTables\HtmlServiceProvider::class,
];
