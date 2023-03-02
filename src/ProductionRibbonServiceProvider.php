<?php

namespace DefStudio\ProductionRibbon;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ProductionRibbonServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('production-ribbon')
            ->hasConfigFile()
            ->hasViews();
    }
}
