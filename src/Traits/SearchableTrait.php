<?php

namespace Aviator\Search\Traits;

use Aviator\Search\Builder;
use Closure;
use Illuminate\Http\Request;

/**
 * Trait SearchableTrait
 * @mixin \Illuminate\Database\Eloquent\Model
 * @property array searches
 */
trait SearchableTrait
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

    /**
     * @param string $searchAlias
     * @param string $requestAlias
     * @param \Illuminate\Http\Request $request
     * @return \Closure
     */
    public function getSearch (string $searchAlias, string $requestAlias, Request $request) : Closure
    {
        return $this->searchBuilder->get($searchAlias, $requestAlias, $request);
    }
}
