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
        $callback = $this->builder->buildCallback();

        $this->assertInstanceOf(Closure::class, $callback);
    }
    
    /** @test */
    public function it_redirects_method_calls_with_magic_call ()
    {
        $callback = $this->builder->name();

        $this->assertInstanceOf(Closure::class, $callback);
    }

    /** @test */
    public function magic_call_throws_an_error_if_the_search_mapping_doesnt_exist ()
    {
        $this->expectException(UndefinedSearch::class);

        $this->builder->somethingThatDoenstExist();
    }

    /** @test */
    public function it_redirects_calls_with_magic_get ()
    {
        $callback = $this->builder->name;

        $this->assertInstanceOf(Closure::class, $callback);
    }

    /** @test */
    public function magic_get_throws_an_error_if_the_search_mapping_doesnt_exist ()
    {
        $this->expectException(UndefinedSearch::class);

        $this->builder->somethingThatDoesntExist;
    }
}
