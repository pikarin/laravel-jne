<?php

namespace Aditia\Jne\Facades;

use Aditia\Jne\Http\Client;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Aditia\Jne\Http\Requests\TrackingResponse tracking(string $awb)
 */
class Jne extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
