<?php

namespace Aviator\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider as ParentServiceProvider;

class ServiceProvider extends ParentServiceProvider
{
    /*
     * Boot the provider.
     */
    public function boot ()
    {
        Builder::macro('search', function () {});
    }
}
