<?php

use DefStudio\ProductionRibbon\ProductionRibbon;
use DefStudio\ProductionRibbon\Tests\Support\Models\User;

it('is enabled by default', function () {
    expect(config('production-ribbon.enabled'))->toBeTrue();
});

it('is not active outside production', function () {
    config()->set('app.env', 'local');
    $ribbon = new ProductionRibbon();
    expect($ribbon->isActive())->toBeFalse();
});

it('can be activated for any environment', function () {
    config()->set('app.env', 'local');
    config()->set('production-ribbon.environments', ['local']);
    $ribbon = new ProductionRibbon();
    expect($ribbon->isActive())->toBeFalse();
});

it('is not active for a guest user', function () {
    $ribbon = new ProductionRibbon();

    expect($ribbon->isActive())->toBeFalse();
});

it('is not active for a generic user', function () {
    $ribbon = new ProductionRibbon();

    fakeUser('foo@fake.com', '123');

    expect($ribbon->isActive())->toBeFalse();
});

it('is active by email', function (string $email, bool $active) {
    fakeUser($email, '123');

    $ribbon = new ProductionRibbon();

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

    $ribbon = new ProductionRibbon();

    expect($ribbon->isActive())->toBeTrue();
});

it('is active with custom check', function () {
    fakeUser('foo@bar.baz', '123');

    ProductionRibbon::filter(function (User $user) {
        return $user->email === 'foo@bar.baz';
    });

    $ribbon = new ProductionRibbon();

    expect($ribbon->isActive())->toBeTrue();
});

it('can inject the ribbon', function () {
    fakeUser();

    $ribbon = new ProductionRibbon();

    expect($ribbon->inject(fakeResponse())->getContent())
        ->toMatchHtmlSnapshot();
});

it('can inject the ribbon to the right', function () {
    config()->set('production-ribbon.position', 'left');
    fakeUser();

    $ribbon = new ProductionRibbon();

    expect($ribbon->inject(fakeResponse())->getContent())
        ->toMatchHtmlSnapshot();
});
