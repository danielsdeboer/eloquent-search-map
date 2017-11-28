<?php

namespace Aviator\Search;

use Aviator\Search\Exceptions\UndefinedSearch;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as Eloquent;
use Illuminate\Http\Request;

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
     * @param string $searchAlias
     * @param string $searchColumn
     * @param string $searchTerm
     * @return \Closure
     */
    public function buildCallback (
        string $searchAlias,
        string $searchColumn,
        string $searchTerm
    ) : Closure
    {
        /**
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @return \Illuminate\Database\Eloquent\Builder
         */
        return function (Eloquent $query) use ($searchAlias, $searchColumn, $searchTerm) {
            return $query->where(
                $searchColumn,
                'like',
                $this->wrapPercent($searchTerm)
            );
        };
    }

    /**
     * Get the closure for the given aliases and request.
     * @param string $searchAlias
     * @param string $requestAlias
     * @param \Illuminate\Http\Request $request
     * @return \Closure
     */
    public function get (string $searchAlias, string $requestAlias, Request $request) : Closure
    {
        return $this->buildCallback(
            $searchAlias,
            $this->column($searchAlias),
            $request->get($requestAlias)
        );
    }

    /**
     * @return array
     */
    public function searches () : array
    {
        return $this->searches;
    }

    /**
     * @param string $alias
     * @return string
     * @throws \Aviator\Search\Exceptions\UndefinedSearch
     */
    public function column (string $alias) : string
    {
        $column = $this->searches[$alias] ?? null;

        if (is_null($column)) {
            throw new UndefinedSearch(
                sprintf(
                    'The search column "%s" was not defined on %s.',
                    $alias,
                    get_class($this->model)
                )
            );
        }

        return $column;
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
        return array_assoc($array);
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

    /**
     * Wrap a string in percentage signs.
     * @param string $string
     * @return string
     */
    protected function wrapPercent (string $string) : string
    {
        return '%' . $string .'%';
    }
}
