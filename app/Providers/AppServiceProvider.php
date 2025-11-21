<?php

namespace App\Providers;

use App\Http\Middleware\APIRequestContainer;
use App\Notifications\Channels\TeamsChannel;
use Aws\Sqs\SqsClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SqsClient::class, function () {
            return new SqsClient([
                'region'      => config('queue.connections.sqs.region'),
                'version'     => 'latest',
                'credentials' => [
                    'key'    => config('queue.connections.sqs.key'),
                    'secret' => config('queue.connections.sqs.secret'),
                ],
                'endpoint'    => config('queue.connections.sqs.endpoint') ?? null,
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (App::isProduction()) {
            URL::forceScheme('https');
        }

        $this->app->singleton(APIRequestContainer::class, function () {
            return new APIRequestContainer();
        });

        $this->app->make(ChannelManager::class)->extend('teams', function ($app) {
            return new TeamsChannel();
        });

        Model::unguard();
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());
        Model::preventLazyLoading(! $this->app->isProduction());
    }
}
