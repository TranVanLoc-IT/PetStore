<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laudis\Neo4j\ClientBuilder;

class NeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Đăng ký client của Neo4j vào container
        $this->app->singleton('neo4j', function ($app) {
            return ClientBuilder::create()
                ->withDriver('bolt', sprintf('bolt://%s:%s@%s:%d', 
                    env('NEO4J_USERNAME', 'neo4j'),
                    env('NEO4J_PASSWORD', ''),
                    env('NEO4J_HOST', 'localhost'),
                    env('NEO4J_PORT', 7687)
                ))
                ->build();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
