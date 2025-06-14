<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ArtigoCreated;
use App\Listeners\PublicaArtigo;

class EventListenerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(
            ArtigoCreated::class,
            [PublicaArtigo::class, 'handle']
        );
    }
}
