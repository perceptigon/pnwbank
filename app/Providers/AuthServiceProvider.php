<?php

namespace App\Providers;

use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define("admin", function ($user) {
           return $user->isAdmin === 1;
        });

        Gate::define("loans", function ($user) {
            return ($user->getPermissions()["loans"] ?? "") === "yes";
        });

        Gate::define("grants", function ($user) {
            return ($user->getPermissions()["grants"] ?? "") === "yes";
        });

        Gate::define("market", function ($user) {
            return ($user->getPermissions()["market"] ?? "") === "yes";
        });

        Gate::define("settings", function ($user) {
            return ($user->getPermissions()["settings"] ?? "") === "yes";
        });

        Gate::define("so", function ($user) { // Stratton Oakmont
            return ($user->getPermissions()["so"] ?? "") === "yes";
        });

        Gate::define("users", function ($user) {
            return ($user->getPermissions()["users"] ?? "") === "yes";
        });

        Gate::define("taxes", function ($user) {
            return ($user->getPermissions()["taxes"] ?? "") === "yes";
        });

        Gate::define("members", function ($user) {
            return ($user->getPermissions()["members"] ?? "") === "yes";
        });

        Gate::define("accounts", function ($user) {
            return ($user->getPermissions()["accounts"] ?? "") === "yes";
        });

        Gate::define("targets", function ($user) {
            return ($user->getPermissions()["targets"] ?? "") === "yes";
        });
    }
}
