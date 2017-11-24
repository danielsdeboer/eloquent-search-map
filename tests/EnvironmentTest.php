<?php

namespace Aviator\Search\Tests;

class EnvironmentTest extends TestCase
{
    /** @test */
    public function the_database_is_populated ()
    {
        $count = $this->make->user->count();

        $this->assertEquals(10, $count);
    }

    /** @test */
    public function the_service_provider_is_loaded ()
    {
        $this->assertArrayHasKey(
            'Aviator\Search\ServiceProvider',
            app()->getLoadedProviders()
        );
    }
}
