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
     * @param string $searchColumn
     * @param string $searchTerm
     * @return \Closure
     */
    public function buildCallback (
        string $searchColumn,
        string $searchTerm = ''
    ) : Closure
    {
        /*
         * Handle relational searches. This is predicated on the relationship model also
         * having a searches callback bag defined.
         */
//        if (str_contains($column, '.')) {
//            return $this->composeRelationCallback($request, $alias, $column);
//        }


    }

    /**
     * Generate the closure.
     * @param string $column
     * @return \Closure
     */
    public function generate (string $column)
    {
        return function ($term) use ($column) {
            return function (Eloquent $query) use ($term, $column) {
                return $query->where(
                    $column,
                    'like',
                    $this->wrapPercent($term)
                );
            };
        };
    }

    /**
     * Get the appropriate column and request data and build the closure.
     * @param string $searchAlias
     * @param string $requestAlias
     * @param \Illuminate\Http\Request $request
     * @return \Closure
     */
    public function get (
        string $searchAlias,
        string $requestAlias,
        Request $request
    ) : Closure
    {
        return $this->generate
            ($this->column($searchAlias))
            ($this->term($request, $requestAlias));
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
            $this->throwUndefinedSearch($alias);
        }

        return $column;
    }

    /**
     * @param array $searches
     * @return \Aviator\Search\Builder
     */
    public function setSearches (array $searches) : Builder
    {
        $this->searches = array_assoc($searches);

        return $this;
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
            sprintf(
                'The search "%s" doesn\'t exist in the searches for %s',
                $search,
                get_class($this->model)
            )

        );
    }

    /**
     * Wrap a string in percentage signs.
     * @param string $string
     * @return string
     */
    protected function wrapPercent (string $string) : string
    {
        return '%' . $string . '%';
    }

    /**
     * Get the search term from the request. If none exists return
     * an empty string.
     * @param \Illuminate\Http\Request $request
     * @param string $alias
     * @return string
     */
    protected function term (Request $request, string $alias) : string
    {
        return $request->get($alias) ?: '';
    }
}
