<?php

namespace Eboseogbidi\Smartpaymentrouter\Tests\Integration;

use Eboseogbidi\Smartpaymentrouter\Tests\TestCase;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class IntegrationTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Eloquent::unguard();

        // $this->loadLaravelMigrations();

        // $this->artisan('migrate')->run();
    }
}
