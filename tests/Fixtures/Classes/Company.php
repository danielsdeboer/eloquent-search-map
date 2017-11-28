<?php

namespace Aviator\Search\Tests\Fixtures\Classes;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /** @var string */
    protected $table = 'companies';

    /** @var array */
    protected $guarded = [];
}
