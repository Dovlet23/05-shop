<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        if (app()->isProduction()) {
            DB::listen(function ($query) {
                if ($query->time > 100) {
                    logger()
                        ->channel('telegram')
                        ->debug('query longer then 1ms:' . $query->sql, $query->bindings);
                }
            });

            app(Kernel::class)->whenRequestLifecycleIslongerThan(
                CarbonInterval::seconds(4),
                function () {
                    logger()
                        ->channel('telegram')
                        ->debug('whenQueryingForLongerThan:' . request()->url());
                }
            );
        }
    }
}

