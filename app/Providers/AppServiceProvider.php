<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\Player;
use App\Models\GameMatch;
use App\Policies\TeamPolicy;
use App\Policies\PlayerPolicy;
use App\Policies\MatchPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register explicit route model binding for GameMatch
        // This is needed because the class name doesn't match the route parameter
        Route::model('match', GameMatch::class);

        // Register model policies
        Gate::policy(Team::class, TeamPolicy::class);
        Gate::policy(Player::class, PlayerPolicy::class);
        Gate::policy(GameMatch::class, MatchPolicy::class);
    }
}
