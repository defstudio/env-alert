<?php

namespace DefStudio\EnvAlert\Tests;

use DefStudio\EnvAlert\EnvAlertServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'DefStudio\\EnvAlert\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            EnvAlertServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.env', 'production');

        config()->set('env-alert.environments', [
            'production' => [
                'filters' => [
                    'email' => [
                        'email@email.test',
                        '*@pattern.com',
                    ],
                    'ip' => [
                        '123.456.789.101',
                        '123.456.789.102',
                    ],
                ],
            ],
        ]);
    }
}
