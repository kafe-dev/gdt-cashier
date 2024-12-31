<?php

use Mailjet\LaravelMailjet\MailjetServiceProvider;
use Mailjet\LaravelMailjet\Providers\ContactsServiceProvider;

return [
    App\Provider\AppServiceProvider::class,
    MailjetServiceProvider::class,
    ContactsServiceProvider::class,
];
