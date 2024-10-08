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
        $this->app->singleton('neo4j', function ($app) {
            return ClientBuilder::create()
                ->withDriver('aura', env('NEO4J_URI'))  // Use the Aura URI directly
                ->build();  // Don't forget to build the client
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
