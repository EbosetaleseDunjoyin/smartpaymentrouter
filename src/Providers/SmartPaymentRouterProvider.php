<?php 

namespace Eboseogbidi\Smartpaymentrouter\Providers;

use Illuminate\Support\ServiceProvider;
use Eboseogbidi\Smartpaymentrouter\Services\PaymentRouter;


class SmartPaymentRouterProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(PaymentRouter::class, function ($app): PaymentRouter {
            return new PaymentRouter();
        });

        $this->mergeConfigFrom(__DIR__ . '/../../config/smartpaymentrouter.php', 'smartpaymentrouter');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        // dd(23);
        $this->publishes([
            __DIR__ . '/../../config/smartpaymentrouter.php' => config_path('smartpaymentrouter.php')
        ], 'smartpaymentrouter-config');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

    }
}