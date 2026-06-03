<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerPasswordRules();
    }

    private function registerPasswordRules(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)->max(255);

            return app()->environment('production')
                ? $rule->mixedCase()->numbers()
                : $rule;
        });
    }
}
