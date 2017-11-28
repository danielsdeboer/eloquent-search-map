<?php

namespace Aviator\Search\Tests\Fixtures\Classes;

use Aviator\Search\Interfaces\Searchable;
use Aviator\Search\Traits\SearchableTrait;

class UserWithSearches extends User implements Searchable
{
    use SearchableTrait;

    /** @var array */
    protected $searches;

    /**
     * Constructor.
     * @param array $searches
     */
    public function __construct (array $searches = [])
    {
        parent::__construct();

        $this->searches = $searches;

        $this->setSearchBuilder();
    }
}
