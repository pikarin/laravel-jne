<?php

namespace Aditia\Jne\Tests;

use Aditia\Jne\Providers\JneServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        //
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            JneServiceProvider::class,
        ];
    }
}
