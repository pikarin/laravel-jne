<?php

namespace Aditia\Jne\Facades;

use Aditia\Jne\Http\Client;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Aditia\Jne\Http\Response\TrackingResponse tracking(string $awb)
 * @method static \Aditia\Jne\Http\Response\GenerateAwbResponse generateAwb(\Aditia\Jne\Http\Requests\Contracts\Request $requestBody)
 * @method static \Aditia\Jne\Http\Response\TariffResponse tariff(\Aditia\Jne\Http\Requests\Contracts\Request $requestBody)
 * @method static \Aditia\Jne\Http\Response\StockAwbResponse stockAwb(\Aditia\Jne\Http\Requests\Contracts\Request $requestBody)
 * @method static \Illuminate\Http\Client\Response post(string $url, array $data)
 * @method static string getUsername()
 * @method static string getApiKey()
 *
 * @see \Illuminate\Http\Request
 */
class Jne extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
