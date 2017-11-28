<?php

namespace Aviator\Search\Tests\Fixtures;

use Aviator\Search\Tests\Fixtures\Abstracts\Gettable;
use Aviator\Search\Tests\Fixtures\Classes\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Model;

class Factory extends Gettable
{
    /** @var \Aviator\Search\Tests\Fixtures\Make */
    private $make;

    /** @var \Faker\Generator */
    private $faker;

    /**
     * Constructor.
     */
    public function __construct ()
    {
        $this->make = new Make;
        $this->faker = Faker::create();
    }

    /**
     * Create a batch of users.
     * @param int $count
     */
    public function companies (int $count = 10)
    {
        $this->batchOf($count, 'company', function (Model $model) {
            return $model->query()->create([
                'city' => $this->faker->city,
                'user_id' => $this->user()->id
            ]);
        });
    }

    /**
     * Create a user model and return it.
     * @return mixed
     */
    public function user ()
    {
        return User::query()->create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ]);
    }

    /**
     * Build a batch of something from the $make object.
     * @param int $count
     * @param string $type
     * @param callable $callable
     */
    protected function batchOf (int $count, string $type, callable $callable)
    {
        /*
         * Create a batch of users.
         */
        for ($i = 0; $i < $count; $i++) {
            $callable($this->make->$type);
        }
    }
}
