<?php

return [
    'enabled' => (bool) env('PRODUCTION_RIBBON_ENABLED', true),

    /*
     * Environments where to show the ribbon alert
     */
    'environments' => ['production'],

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
     * The position of the ribbon alert.
     * Accepted values: left/right
     */
    'position' => 'left',
];
