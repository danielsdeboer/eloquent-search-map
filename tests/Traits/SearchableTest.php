<?php

namespace Aviator\Search\Tests\Models;

use Aviator\Search\Builder;
use Aviator\Search\Tests\TestCase;

class SearchableTest extends TestCase
{
    /** @test */
    public function if_searches_are_set_searches_returns_them ()
    {
        $model = $this->make->userWithSearches;

        $this->assertGreaterThan(0, count($model->searches()));
        $this->assertContains('name', $model->searches());
    }

    /** @test */
    public function it_sets_a_callback_builder ()
    {
        $model = $this->make->userWithSearches;

        $model->setSearchBuilder();

        $this->assertObjectHasAttribute('searchBuilder', $model);
        $this->assertAttributeInstanceOf(
            Builder::class,
            'searchBuilder',
            $model
        );
    }
}
