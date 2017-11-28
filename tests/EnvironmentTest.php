<?php

namespace Aviator\Search\Tests;

use Aviator\Search\Tests\Fixtures\Classes\User;

class EnvironmentTest extends TestCase
{
    /** @test */
    public function the_database_is_populated ()
    {
        $counts = collect([
            $this->make->user->count(),
            $this->make->company->count(),
        ]);

        $test = function ($count) {
            $this->assertEquals(10, $count);
        };

        $counts->each($test);
    }

    /** @test */
    public function the_service_provider_is_loaded ()
    {
        $this->assertArrayHasKey(
            'Aviator\Search\ServiceProvider',
            app()->getLoadedProviders()
        );
    }

    /** @test */
    public function the_user_has_a_company_relation ()
    {
        $users = User::query()->get();

        $test = function ($user) {
            $this->assertNotNull($user->company);
        };

        $users->each($test);
    }
}
