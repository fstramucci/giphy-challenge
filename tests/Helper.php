<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

trait Helper
{
    protected function createPersonalClient()
    {
        \Artisan::call('passport:install --no-interaction');
        \Artisan::call('db:seed');
    }
}