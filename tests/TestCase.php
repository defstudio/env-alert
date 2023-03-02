<?php

namespace DefStudio\ProductionRibbon\Tests;

use DefStudio\ProductionRibbon\ProductionRibbonServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'DefStudio\\ProductionRibbon\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ProductionRibbonServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.env', 'production');

        config()->set('production-ribbon.filters.ip', [
            '123.456.789.101',
            '123.456.789.102',
        ]);

        config()->set('production-ribbon.filters.email', [
            'email@email.test',
            '*@pattern.com',
        ]);
    }
}
