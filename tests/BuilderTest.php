<?php

namespace Aviator\Search\Tests;

use Aviator\Search\Builder;
use Aviator\Search\Exceptions\UndefinedSearch;
use Closure;

class BuilderTest extends TestCase
{
    /** @var \Illuminate\Database\Eloquent\Model */
    private $model;

    /** @var \Aviator\Search\Builder */
    private $builder;

    /**
     * Perform environment setup.
     */
    protected function setUp ()
    {
        parent::setUp();

        $this->model = $this->make->userWithSearches;
        $this->builder = new Builder($this->model, $this->model->searches());
    }
    
    /** @test */
    public function it_converts_non_associative_arrays_to_associative ()
    {
        $expected = ['name' => 'name'];

        $this->assertSame($expected, $this->builder->searches());
    }

    /** @test */
    public function it_converts_mixed_arrays_to_fully_associative ()
    {
        $expected = ['name' => 'name', 'test' => 'email'];

        $model = $this->make->userWithMixedSearches;
        $this->builder = new Builder($model, $model->searches());

        $this->assertSame($expected, $this->builder->searches());
    }

    /** @test */
    public function it_composes_callbacks_for_searches ()
    {
        $callback = $this->builder->generate('column')('term');

        $this->assertInstanceOf(Closure::class, $callback);
    }

    /** @test */
    public function get_method_looks_up_the_column_and_request_payload_and_returns_the_callback ()
    {
        $callback = $this->builder->get(
            'name',
            'name',
            request()
        );

        $this->assertInstanceOf(Closure::class, $callback);
    }

    /** @test */
    public function it_throws_an_exception_if_the_search_isnt_defined ()
    {
        $this->expectException(UndefinedSearch::class);

        $this->builder->get('undefined-search', 'name', request());
    }
}
