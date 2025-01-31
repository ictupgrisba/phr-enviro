<?php

namespace App\Providers;

use App\Mapper\Contracts\IPostMapper;
use App\Mapper\Contracts\IUserMapper;
use App\Mapper\Contracts\IWorkTripMapper;
use App\Mapper\PostMapper;
use App\Mapper\UserMapper;
use App\Mapper\WorkTripMapper;
use App\Utils\Contracts\IUtility;
use App\Utils\Utility;
use Illuminate\Support\ServiceProvider;

class PhrMapperProvider extends ServiceProvider
{
    /**
     * Register services.
     */

    public function register(): void
    {
        $this->app->bind(IUtility::class, Utility::class);
        $this->app->bind(IUserMapper::class, UserMapper::class);
        $this->app->bind(IPostMapper::class, PostMapper::class);
        $this->app->bind(IWorkTripMapper::class, WorkTripMapper::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
