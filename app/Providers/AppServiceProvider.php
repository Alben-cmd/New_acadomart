<?php

namespace App\Providers;

use App\Http\Responses\CustomLoginResponse;
use App\Http\Responses\CustomRegistrationResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as RegistrationResponseContract;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use App\Filesystem\VercelBlobAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, CustomLoginResponse::class);
        $this->app->singleton(RegistrationResponseContract::class, CustomRegistrationResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void 
    {
        Schema::defaultStringLength(191);

        Storage::extend('vercel-blob', function ($app, $config) {
            $token = $config['token'] ?? env('BLOB_READ_WRITE_TOKEN');
            $adapter = new VercelBlobAdapter($token);
            
            return new \Illuminate\Filesystem\FilesystemAdapter(
                new Filesystem($adapter),
                $adapter,
                $config
            );
        });
    }
}
