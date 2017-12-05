<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        $this->mapSpiderRoutes();

        $this->mapInnerRoutes();

        $this->mapMerchantRoutes();

        $this->mapAgentRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapMerchantRoutes()
    {
        Route::prefix('merchant')
            ->middleware('merchant')
            ->namespace($this->namespace)
            ->group(base_path('routes/merchant.php'));
    }

    protected function mapAgentRoutes()
    {
        Route::prefix('agent')
            ->middleware('agent')
            ->namespace($this->namespace)
            ->group(base_path('routes/agent.php'));
    }

    protected function mapInnerRoutes()
    {
        Route::prefix('inner')
            ->namespace($this->namespace)
            ->group(base_path('routes/inner.php'));
    }

    protected function mapSpiderRoutes()
    {
        Route::prefix('spider')
            ->namespace($this->namespace)
            ->group(base_path('routes/spider.php'));
    }
}
