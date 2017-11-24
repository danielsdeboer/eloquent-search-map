<?php

namespace Aviator\Search;

use Aviator\Search\Exceptions\UndefinedSearch;
use Closure;
use Illuminate\Database\Eloquent\Model;

class Builder
{
    /** @var \Illuminate\Database\Eloquent\Model */
    private $model;

    /** @var array */
    private $searches;

    /**
     * Constructor.
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $searches
     */
    public function __construct (Model $model, array $searches)
    {
        $this->model = $model;
        $this->setSearches($searches);
    }

    /**
     * @return \Closure
     */
    public function buildCallback () : Closure
    {
        return function () {};
//        return function (Builder $query) use () {
//            return $query->where($column, 'like', '%' . $request->$alias . '%');
//        };
    }

    /** @return array */
    public function searches () : array
    {
        return $this->searches;
    }

    /**
     * @param array $searches
     * @return \Aviator\Search\Builder
     */
    public function setSearches (array $searches) : Builder
    {
        $this->searches = $this->normalizeArray($searches);

        return $this;
    }

    /**
     * Convert a given non-associative or mixed array to an
     * associative array.
     * @param array $array
     * @return array
     */
    protected function normalizeArray (array $array) : array
    {
        return array_map_with_keys($array, function ($key, $value) {
           return [
               is_numeric($key) ? $value : $key,
               $value
           ];
        });
    }

    protected function prepareArguments ($search, $arguments)
    {

    }

    /**
     * Redirect method calls to the callback builder.
     * @param $search
     * @param array $arguments
     * @return \Closure
     */
    public function __call ($search, $arguments) : Closure
    {
        if ($this->searchExists($search)) {
            return $this->buildCallback(
//                ...$this->prepareArguments($search, $arguments)
            );
        }

        $this->throwUndefinedSearch($search);
    }

    /**
     * Redirect property calls to the callback builder.
     * @param $search
     * @return \Closure
     */
    public function __get ($search) : Closure
    {
        if ($this->searchExists($search)) {
            return $this->buildCallback();
        }

        $this->throwUndefinedSearch($search);
    }

    /**
     * Check if the given search exists in the searches array.
     * @param $search
     * @return bool
     */
    protected function searchExists ($search) : bool
    {
        return in_array($search, $this->searches);
    }

    /**
     * @param $search
     * @throws \Aviator\Search\Exceptions\UndefinedSearch
     */
    protected function throwUndefinedSearch ($search)
    {
        throw new UndefinedSearch(
            'The search "' . $search . '" doesn\'t exist in the searches array.'
        );
    }
}
