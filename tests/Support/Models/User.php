<?php

namespace DefStudio\ProductionRibbon\Tests\Support\Models;

/**
 * @method static User create(array $array)
 * @method static User make(array $array)
 */
class User extends \Illuminate\Foundation\Auth\User
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];
}
