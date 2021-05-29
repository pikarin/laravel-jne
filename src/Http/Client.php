<?php

namespace Aditia\Jne\Http;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Aditia\Jne\Http\Response\TariffResponse;
use Aditia\Jne\Http\Response\StockAwbResponse;
use Aditia\Jne\Http\Response\TrackingResponse;
use Aditia\Jne\Http\Requests\Contracts\Request;
use Aditia\Jne\Http\Response\GenerateAwbResponse;

class Client
{
    const TRACKING_URL = '/tracing/api/list/v1/cnote';
    const GENERATE_AWB_URL = '/tracing/api/generatecnote';
    const TARIFF_URL = '/tracing/api/pricedev';
    const PICKUP_URL = '/pickupcashless';
    const STOCK_AWB_URL = '/tracing/api/stockawb';

    protected string $baseUrl;
    protected string $username;
    protected string $key;

    public function __construct(
        string $baseUrl,
        string $username,
        string $key
    ) {
        $this->baseUrl = $baseUrl;
        $this->username = $username;
        $this->key = $key;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getApiKey(): string
    {
        return $this->key;
    }

    public function tracking(string $awb): TrackingResponse
    {
        $response = $this->post(self::TRACKING_URL."/$awb", [
            'username' => $this->getUsername(),
            'key' => $this->getApiKey(),
        ]);

        return new TrackingResponse(
            $response->json(),
            $response->status(),
            $response->headers()
        );
    }

    public function generateAwb(Request $requestBody): GenerateAwbResponse
    {
        $requestBody->setCredentials($this->getUsername(), $this->getApiKey());

        $requestBody->validate();

        $response = $this->post(self::GENERATE_AWB_URL, $requestBody->toArray());

        return new GenerateAwbResponse(
            $response->json(),
            $response->status(),
            $response->headers()
        );
    }

    public function tariff(Request $requestBody): TariffResponse
    {
        $requestBody->setCredentials($this->getUsername(), $this->getApiKey());

        $requestBody->validate();

        $response = $this->post(self::TARIFF_URL, $requestBody->toArray());

        return new TariffResponse(
            $response->json(),
            $response->status(),
            $response->headers()
        );
    }

    public function pickup(Request $requestBody)
    {
        //
    }

    public function stockAwb(Request $requestBody): StockAwbResponse
    {
        $requestBody->setCredentials($this->getUsername(), $this->getApiKey());

        $requestBody->validate();

        $response = $this->post(self::STOCK_AWB_URL, $requestBody->toArray());

        return new StockAwbResponse(
            $response->json(),
            $response->status(),
            $response->headers(),
        );
    }


    public function post(string $url, array $data): Response
    {
        return Http::baseUrl($this->baseUrl)->asForm()->post($url, $data);
    }
}
