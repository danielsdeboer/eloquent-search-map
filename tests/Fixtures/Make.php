<?php

namespace Aviator\Search\Tests\Fixtures;

use Aviator\Search\Tests\Fixtures\Abstracts\Gettable;
use Aviator\Search\Tests\Fixtures\Classes\Company;
use Aviator\Search\Tests\Fixtures\Classes\User;
use Aviator\Search\Tests\Fixtures\Classes\UserWithSearches;

/**
 * @property \Aviator\Search\Tests\Fixtures\Classes\User user
 * @property \Aviator\Search\Tests\Fixtures\Classes\UserWithSearches userWithEmptySearches
 * @property \Aviator\Search\Tests\Fixtures\Classes\UserWithSearches userWithMixedSearches
 * @property \Aviator\Search\Tests\Fixtures\Classes\UserWithSearches userWithSearches
 * @property \Aviator\Search\Tests\Fixtures\Classes\Company company
 */
class Make extends Gettable
{
    /**
     * Get a non-Searchable company model.
     * @return \Aviator\Search\Tests\Fixtures\Classes\Company
     */
    public function company ()
    {
        return new Company;
    }

    /**
     * Get a non-Searchable user model.
     * @return \Aviator\Search\Tests\Fixtures\Classes\User
     */
    public function user ()
    {
        return new User;
    }

    /**
     * Get a Searchable user with unaliased searches.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function userWithSearches ()
    {
        return new UserWithSearches(['name']);
    }

    /**
     * Get a Searchable user with a mix of aliased and unaliased searches.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function userWithMixedSearches ()
    {
        return new UserWithSearches(['name', 'test' => 'email']);
    }

    /**
     * Get a Searchable user with no searches.
     * @return \Aviator\Search\Tests\Fixtures\Classes\UserWithSearches
     */
    public function userWithEmptySearches ()
    {
        return new UserWithSearches([]);
    }
}
