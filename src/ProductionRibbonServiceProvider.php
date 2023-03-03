<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\ProductionRibbon;

use DefStudio\ProductionRibbon\Middleware\InjectRibbon;
use Illuminate\Contracts\Http\Kernel;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ProductionRibbonServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('production-ribbon')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(InjectRibbon::class);
    }
}
