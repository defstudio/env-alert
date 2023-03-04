<?php

return [
    'enabled' => (bool) env('PRODUCTION_RIBBON_ENABLED', true),

    /*
     * Current environment, taken from APP_ENV or PRODUCTION_RIBBON_ENV .env entries
     */
    'current_environment' => env('PRODUCTION_RIBBON_ENV', env('APP_ENV', 'production')),

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
                'background_color' => '#D70040',
                'text_color' => '#D70040',
            ],
        ],
    ],

    'service_class' => \DefStudio\ProductionRibbon\ProductionRibbon::class
];
