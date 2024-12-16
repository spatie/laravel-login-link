<?php

namespace Spatie\LoginLink\Tests\TestSupport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as IlluminateUser;

class Customer extends IlluminateUser
{
    protected $table = 'customers';

    use HasFactory;
}
