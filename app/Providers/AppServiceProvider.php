<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\User;
use App\Policies\Api\V1\CommentPolicy;
use App\Policies\Api\V1\UserPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(app()->isLocal());
        //Broadcast for api with auth
        Broadcast::routes(['prefix' => 'api', 'middleware' => 'auth:sanctum']);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
