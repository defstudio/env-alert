<?php

use DefStudio\ProductionRibbon\Tests\Support\Models\User;
use DefStudio\ProductionRibbon\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function Pest\Laravel\actingAs;

uses(TestCase::class)->in(__DIR__);

function fakeUser(string $email = 'email@email.test', string $ip = '123.456.789.101'): User
{
    $user = new User();
    $user->email = $email;

    actingAs($user);

    $request = new class extends Request
    {
        private string $__ip = '';

        public function setIp(string $ip)
        {
            $this->__ip = $ip;
        }

        public function ip()
        {
            return $this->__ip;
        }
    };

    $request->setIp($ip);

    app()->bind('request', fn () => $request);

    return $user;
}

function fakeResponse(): Response
{
    $response = new Response();
    $response->setContent(<<<'HTML'
            <html>
                <head>
                    <title>Fake Page</title>
                </head>
                <body>
                    <div>content</div>
                </body>
            </html>
            HTML
    );

    return $response;
}
