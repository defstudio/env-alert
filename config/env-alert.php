<?php

return [
    'enabled' => (bool) env('ENV_ALERT_ENABLED', true),

    /*
     * Current environment, taken from APP_ENV or ENV_ALERT_ENV .env entries
     */
    'current_environment' => env('ENV_ALERT_ENV', env('APP_ENV', 'production')),

    /*
     * Environments where to show the ribbon alert
     */
    'environments' => [
        'production' => [
            /*
             * When to display the ribbon alert.
             */
            'filters' => [
                'email' => [
                    // 'your.email@email.test',
                    // '*@your.company.com'
                ],
                'ip' => [
                    // '123.456.789.101'
                ],
            ],

            /*
             * The ribbon style
             */
            'style' => [
                'position' => 'right',
                'background_color' => '#f30b0b',
                'text_color' => '#ffffff',
            ],
        ],
    ],

    'service_class' => \DefStudio\EnvAlert\RibbonService::class,
];
