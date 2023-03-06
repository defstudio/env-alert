<?php

use DefStudio\EnvAlert\RibbonService;
use DefStudio\EnvAlert\Tests\Support\Models\User;

beforeEach(fn () => RibbonService::resetFilters());

it('is enabled by default', function () {
    expect(config('env-alert.enabled'))->toBeTrue();
});

it('is not active outside production', function () {
    config()->set('app.env', 'local');
    $ribbon = new RibbonService();
    expect($ribbon->isActive())->toBeFalse();
});

it('can be activated for any environment', function () {
    config()->set('app.env', 'local');
    config()->set('env-alert.environments', ['local']);
    $ribbon = new RibbonService();
    expect($ribbon->isActive())->toBeFalse();
});

test('a custom environment can be set', function () {
    config()->set('env-alert.current_environment', 'foo');
    config()->set('env-alert.environments', [
        'foo' => [
            'filters' => ['email' => ['email@email.test']],
        ],
    ]);

    fakeUser();

    $ribbon = new RibbonService();
    expect($ribbon->isActive())->toBeTrue();
});

it('is not active for a guest user', function () {
    $ribbon = new RibbonService();

    expect($ribbon->isActive())->toBeFalse();
});

it('is not active for a generic user', function () {
    $ribbon = new RibbonService();

    fakeUser('foo@fake.com', '123');

    expect($ribbon->isActive())->toBeFalse();
});

it('is active by email', function (string $email, bool $active) {
    fakeUser($email, '123');

    $ribbon = new RibbonService();

    expect($ribbon->isActive())->toBe($active);
})->with([
    'exact email' => [
        'email' => 'email@email.test',
        'active' => true,
    ],
    'email pattern' => [
        'email' => 'foo@pattern.com',
        'active' => true,
    ],
]);

it('is active with ip match', function () {
    fakeUser('foo@bar.baz', '123.456.789.102');

    $ribbon = new RibbonService();

    expect($ribbon->isActive())->toBeTrue();
});

it('is active with custom check', function () {
    fakeUser('foo@bar.baz', '123');

    RibbonService::filter(function (User|null $user) {
        return $user->email === 'foo@bar.baz';
    });

    $ribbon = new RibbonService();

    expect($ribbon->isActive())->toBeTrue();
});

it('can inject the ribbon to the left', function () {
    fakeUser();
    config()->set('env-alert.environments.production.style.position', 'left');

    $ribbon = new RibbonService();

    expect($ribbon->inject(fakeResponse())->getContent())
        ->toMatchHtmlSnapshot();
});

it('can inject the ribbon to the right', function () {
    fakeUser();
    config()->set('env-alert.environments.production.style.position', 'right');

    $ribbon = new RibbonService();

    expect($ribbon->inject(fakeResponse())->getContent())
        ->toMatchHtmlSnapshot();
});

it('can change the ribbon style', function () {
    fakeUser();
    config()->set('env-alert.environments.production.style', [
        'position' => 'left',
        'background_color' => '#123456',
        'text_color' => '#987654',
    ]);

    $ribbon = new RibbonService();

    expect($ribbon->inject(fakeResponse())->getContent())
        ->toMatchHtmlSnapshot();
});

it('can customize the ribbon text', function () {
    fakeUser();
    config()->set('env-alert.environments.production.text', 'foo');

    $ribbon = new RibbonService();

    expect($ribbon->inject(fakeResponse())->getContent())
        ->toMatchHtmlSnapshot();
});
