<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\EnvAlert;

use DefStudio\EnvAlert\Middleware\InjectRibbon;
use Illuminate\Contracts\Http\Kernel;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class EnvAlertServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('env-alert')
            ->hasConfigFile()
            ->hasViews('ribbon');
    }

    public function packageRegistered(): void
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(InjectRibbon::class);
    }
}
