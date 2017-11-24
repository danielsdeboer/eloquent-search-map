<?php

namespace Aviator\Search\Traits;

use Aviator\Search\Builder;

/**
 * Trait Searchable
 * @mixin \Illuminate\Database\Eloquent\Model
 * @property array searches
 */
trait Searchable
{
    /** @var \Aviator\Search\Builder */
    protected $searchBuilder;

    /**
     * Get the searchable attribute mapping.
     * @return array
     */
    public function searches ()
    {
        return property_exists($this, 'searches')
            ? $this->searches
            : [];
    }

    /**
     * Set the search generator instance.
     * @return $this
     */
    public function setSearchBuilder ()
    {
        $this->searchBuilder = new Builder($this, $this->searches());

        return $this;
    }
}
