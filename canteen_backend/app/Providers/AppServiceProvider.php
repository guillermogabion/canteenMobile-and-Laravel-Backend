<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('decimal', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\d+(\.\d{1,2})?$/', $value);
        });

        Validator::replacer('decimal', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, ':attribute must be a valid decimal number with up to two decimal places.');
        });
    }
}
