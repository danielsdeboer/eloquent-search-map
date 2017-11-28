<?php

namespace Aviator\Search\Tests;

use Aviator\Search\Exceptions\ModelNotSearchable;
use Aviator\Search\Exceptions\UndefinedSearch;
use Aviator\Search\Tests\Fixtures\Classes\Company;
use Aviator\Search\Tests\Fixtures\Classes\User;
use Illuminate\Http\Request;

class MacroTest extends TestCase
{
    /** @test */
    public function it_constrains_queries_when_request_contains_search_terms ()
    {
        $userToSearch = User::first();
        $request = new Request(['name' => $userToSearch->name]);

        $search = $this->make->userWithSearches->search(['name'], $request)->get();

        $this->assertSame($userToSearch->name, $search->first()->name);
        $this->assertCount(1, $search);
    }

    /** @test */
    public function it_doesnt_constrain_queries_when_request_doesnt_contain_terms ()
    {
        $request = new Request([]);

        $search = $this->make->userWithSearches->search(['name'], $request)->get();

        $this->assertCount(User::all()->count(), $search);
    }

    /** @test */
    public function it_throws_an_exception_if_the_model_isnt_searchable ()
    {
        $this->expectException(ModelNotSearchable::class);

        $this->make->user->search(['for-something']);
    }

    /** @test */
    public function it_can_use_aliased_request_terms ()
    {
        $userToSearch = User::first();
        $request = new Request(['user_name' => $userToSearch->name]);

        $search = $this->make->userWithSearches->search(['name' => 'user_name'], $request)->get();

        $this->assertCount(1, $search);
        $this->assertSame($userToSearch->name, $search->first()->name);
    }

    /** @test */
    public function it_throws_an_exception_for_undefined_searches ()
    {
        $this->expectException(UndefinedSearch::class);

        $this->make->userWithSearches->search(['some-undefined-alias']);
    }

    /** @test */
    public function it_searches_in_relations_with_dot_notation_converting_dot_notation_to_underscores ()
    {
        $companyToSearch = Company::query()
            ->inRandomOrder()
            ->first();

        $request = new Request(
            ['company_city' => $companyToSearch->city]
        );

        $search = $this->make->userWithRelationSearch
            ->search(['company.city'], $request)
            ->get();

        $this->assertSame($companyToSearch->city, $search->first()->company->city);
        $this->assertCount(1, $search);
    }
}
