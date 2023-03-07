<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Main Switch
    |--------------------------------------------------------------------------
    |
    | This value is used to enable the plugin. It can be done both
    | from .env file (using the ENV_ALERT_ENABLED variable) and
    | by directly overriding the value below.
    |
    */

    'enabled' => (bool) env('ENV_ALERT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Current environment
    |--------------------------------------------------------------------------
    |
    | Here is detected the application actual environment. It is used to
    | to decide if the alert is enabled for that specific environment.
    | It usually takes the value for APP_ENV .env value, but it can
    | be overridden setting the ENV_ALERT_CURRENT_ENV value
    |
    */

    'current_environment' => env('ENV_ALERT_CURRENT_ENV', env('APP_ENV', 'production')),

    /*
    |--------------------------------------------------------------------------
    | Environment settings
    |--------------------------------------------------------------------------
    |
    | Here are each of the environment configurations. Each environment can
    | have its own settings and define custom filters and styles
    |
    */

    'environments' => [
        'production' => [
            'filters' => [
                'email' => [
                    // 'your.email@email.test',
                    // '*@your.company.com'
                ],
                'ip' => [
                    // '123.456.789.101'
                ],
            ],

            'style' => [
                'position' => 'right',
                'background_color' => '#f30b0b',
                'text_color' => '#ffffff',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | RibbonService class
    |--------------------------------------------------------------------------
    |
    | The alert generation service can be easily extended to change and/or
    | extend its behaviour. The custom AlertService class must extend
    | \DefStudio\EnvAlert\AlertService class.
    |
    */

    'service_class' => \DefStudio\EnvAlert\AlertService::class,
];
