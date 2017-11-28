<?php

namespace Aviator\Search\Interfaces;

use Illuminate\Http\Request;

interface Searchable
{
    /**
     * @param string $searchAlias
     * @param string $requestAlias
     * @param \Illuminate\Http\Request $request
     * @return \Closure
     */
    public function getSearch (string $searchAlias, string $requestAlias, Request $request) : \Closure;
}
