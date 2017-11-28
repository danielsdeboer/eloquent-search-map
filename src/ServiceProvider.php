<?php

namespace Aviator\Search;

use Aviator\Search\Exceptions\ModelNotSearchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider as ParentServiceProvider;

class ServiceProvider extends ParentServiceProvider
{
    /**
     * Boot the provider.
     */
    public function boot ()
    {
        Builder::macro('search', $this->callback());
    }

    /**
     * Get the macro callback.
     * @return \Closure
     */
    protected function callback ()
    {
        /**
         * @param array $searches
         * @param \Illuminate\Http\Request|null $request
         * @return $this
         */
        return function (array $searches, Request $request = null) {
            /** @var \Illuminate\Database\Eloquent\Builder $context */
            $context = $this;
            /** @var \Illuminate\Http\Request $request */
            $request = $request ?: request();

            ModelNotSearchable::assertSearchable('Builder Search Macro', $context->getModel());

            foreach (array_assoc($searches) as $searchAlias => $requestAlias) {
                $requestAlias = dot_to_underscore($requestAlias);

                $context->when(
                    $request->get($requestAlias),
                    $context->getModel()->getSearch($searchAlias, $requestAlias, $request)
                );
            }

            return $context;
        };
    }
}
