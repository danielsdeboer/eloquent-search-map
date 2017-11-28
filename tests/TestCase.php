<?php

namespace Aviator\Search\Tests;

use Aviator\Search\Fixtures\Migrations\CreateUsersTable;
use Aviator\Search\ServiceProvider;
use Aviator\Search\Tests\Fixtures\Factory;
use Aviator\Search\Tests\Fixtures\Make;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /** @var \Aviator\Search\Tests\Fixtures\Make */
    public $make;

    /** @var \Aviator\Search\Tests\Fixtures\Factory */
    public $factory;

    /**
     * Perform environment setup.
     */
    protected function setUp ()
    {
        parent::setUp();

        $this->make = new Make;
        $this->factory = new Factory;

        $this->withFactories(__DIR__ . '/Fixtures/Factories');

        $this->setUpDatabase();
    }

    /**
     * Get package providers.
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders ($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * Set up the environment.
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp ($app)
    {
        $app['config']->set('app.debug', 'true');
        $app['config']->set('app.key', 'base64:2+SetJaztC7g0a1sSF81LYsDasiWymO6tp8yVv6KGrA=');

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Set up the database.
     */
    protected function setUpDatabase ()
    {
        include_once __DIR__ . '/Fixtures/Migrations/2017_12_12_000000_create_users_table.php';

        (new CreateUsersTable())->up();

        $this->factory->companies();
    }
}
