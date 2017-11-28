<?php

namespace Aviator\Search\Tests;

use Aviator\Search\Exceptions\ModelNotSearchable;
use Aviator\Search\Exceptions\UndefinedSearch;
use Aviator\Search\Tests\Fixtures\Classes\User;
use Illuminate\Http\Request;

class MacroTest extends TestCase
{
    /** @test */
    public function it_constrains_queries_when_request_contains_search_terms ()
    {
        $userToSearch = User::first();
        $request = new Request(['name' => $userToSearch->name]);

        $search = User::search(['name'], $request)->get();

        $this->assertCount(1, $search);
    }

    /** @test */
    public function it_doesnt_constrain_queries_when_request_doesnt_contain_terms ()
    {
        $request = new Request([]);

        $search = User::search(['name'], $request)->get();

        $this->assertCount(User::all()->count(), $search);
    }

    /** @test */
    public function it_throws_an_exception_if_the_model_isnt_searchable ()
    {
        $this->expectException(ModelNotSearchable::class);

        $this->make->nonSearchClass->search(['for-something']);
    }

    /** @test */
    public function it_can_use_aliased_request_terms ()
    {
        $userToSearch = User::first();
        $request = new Request(['user_name' => $userToSearch->name]);

        $search = User::search(['name' => 'user_name'], $request)->get();

        $this->assertCount(1, $search);
    }

    /** @test */
    public function it_throws_an_exception_for_undefined_searches ()
    {
        $this->expectException(UndefinedSearch::class);

        User::search(['some-undefined-alias']);
    }
}
