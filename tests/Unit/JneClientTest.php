<?php

namespace Aditia\Jne\Tests\Unit;

use Aditia\Jne\Http\Client;
use Aditia\Jne\Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class JneClientTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('jne.api', [
            'username' => 'jne-api-username',
            'key' => 'jne-api-key',
            'url' => 'http://jne-api-url',
        ]);
    }

    /** @test */
    public function it_can_instantiate_the_jne_client_correctly()
    {
        $jne = $this->app->make(Client::class);

        $this->assertInstanceOf(Client::class, $jne);
        $this->assertEquals('jne-api-username', $jne->getUsername());
        $this->assertEquals('jne-api-key', $jne->getApiKey());
    }
}
