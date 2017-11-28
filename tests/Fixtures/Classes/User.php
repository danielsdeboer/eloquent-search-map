<?php

namespace Aviator\Search\Tests\Fixtures\Classes;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /** @var string */
    protected $table = 'users';

    /** @var array */
    protected $guarded = [];

    /** @return \Illuminate\Database\Eloquent\Relations\HasOne */
    public function company ()
    {
        return $this->hasOne(Company::class, 'user_id', 'id');
    }
}
