<?php

namespace Aviator\Search\Tests\Fixtures;

use Aviator\Search\Tests\Fixtures\Abstracts\Gettable;
use Aviator\Search\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Database\Eloquent\Model userWithMixedSearches
 */
class Make extends Gettable
{

    /**
     * Generate an anonymous user class with the given searches.
     * @param array $searches
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function userClass ($searches = [])
    {
        return new class($searches) extends Model {
            use Searchable;

            /** @var string */
            protected $table = 'users';

            /** @var array */
            protected $searches;

            /** @param array $searches */
            public function __construct (array $searches)
            {
                parent::__construct([]);
                $this->searches = $searches;
            }
        };
    }
    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function user ()
    {
        return $this->userClass();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function userWithSearches ()
    {
        return $this->userClass(['name']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function userWithMixedSearches ()
    {
        return $this->userClass(['name', 'test' => 'email']);
    }
}
