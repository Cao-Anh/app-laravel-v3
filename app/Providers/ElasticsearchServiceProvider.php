<?php


namespace App\Providers;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function ($app) {
            $hosts = [
                sprintf(
                  '%s://%s:%s',
                  config('services.elasticsearch.scheme', 'http'),
                  config('services.elasticsearch.host', env('ELASTICSEARCH_HOST')),
                  config('services.elasticsearch.port', env('ELASTICSEARCH_PORT'))
                ),
            ];

            return ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
