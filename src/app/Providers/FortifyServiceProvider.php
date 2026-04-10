<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::loginView(function () 
        {
            return view('auth.login');
        });

        Fortify::registerView(function () 
        {
            return view('auth.register');
        });
     
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::redirects('login', function () 
        {
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                return route('verification.notice');
            }

            if (!$user->profile_completed) {
                return '/mypage/edit';
            }

            return '/';
        });

        Fortify::redirects('register', function () 
        {
            return route('verification.notice');
        });
    }
}
