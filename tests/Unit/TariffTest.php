<?php

namespace Aditia\Jne\Tests\Unit;

use Aditia\Jne\Facades\Jne;
use Aditia\Jne\Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Aditia\Jne\Http\Response\Tariff\Price;
use Aditia\Jne\Http\Requests\TariffRequest;
use Aditia\Jne\Http\Response\TariffResponse;
use Aditia\Jne\Http\Exceptions\InvalidTariffRequestException;

class TariffTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('jne.api', [
            'username' => 'jne-api-username',
            'key' => 'jne-api-key',
            'url' => 'https://jne-api-url',
        ]);
    }

    /** @test */
    public function it_send_to_tariff_api_correctly()
    {
        Http::fake([
            '*' => Http::response($this->successResponse()),
        ]);

        Jne::tariff($this->requestBody());

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Content-Type', 'application/x-www-form-urlencoded') &&
                $request->url() == 'https://jne-api-url/tracing/api/pricedev' &&
                $request['username'] == 'jne-api-username' &&
                $request['api_key'] == 'jne-api-key' &&
                $request['from'] == 'origin-code' &&
                $request['thru'] == 'destination-code' &&
                $request['weight'] == 1;
        });
    }

    /** @test */
    public function it_sets_tariff_response_prices_from_success_response()
    {
        Http::fake([
            '*' => Http::response($this->successResponse()),
        ]);

        $response = Jne::tariff($this->requestBody());

        tap($response->price, function ($price) {
            $this->assertInstanceOf(Collection::class, $price);
            $this->assertEquals(7, $price->count());
        });

        tap($response->price->first(), function ($price) {
            $this->assertInstanceOf(Price::class, $price);
             $this->assertEquals("JAKARTA", $price->origin_name);
             $this->assertEquals("BUNGURSARI , TASIKMALAYA", $price->destination_name);
             $this->assertEquals("JTR", $price->service_display);
             $this->assertEquals("JTR18", $price->service_code);
             $this->assertEquals("Paket", $price->goods_type);
             $this->assertEquals("IDR", $price->currency);
             $this->assertEquals("40000", $price->price);
             $this->assertEquals("3", $price->etd_from);
             $this->assertEquals("4", $price->etd_thru);
             $this->assertEquals("D", $price->times);
        });
    }

    /** @test */
    public function it_validates_before_sending_tariff_request()
    {
        $this->expectException(InvalidTariffRequestException::class);

        Http::fake([
            '*' => Http::response($this->successResponse()),
        ]);

        $response = Jne::tariff(new TariffRequest([]));

        Http::assertNothingSent();
    }

    /** @test */
    public function it_return_correct_response_for_tariff_requests()
    {
        Http::fake([
            '*' => Http::response([
                'error' => 'Cnote No. Not Found.',
                'status' => false,
            ]),
        ]);

        $response = Jne::tariff($this->requestBody());

        $this->assertInstanceOf(TariffResponse::class, $response);
    }

    protected function requestBody(): TariffRequest
    {
        return new TariffRequest([
            'from' => 'origin-code',
            'thru' => 'destination-code',
            'weight' => 1,
        ]);
    }

    protected function successResponse(): array
    {
        return [
            "price" => [
                [
                    "origin_name" => "JAKARTA",
                    "destination_name" => "BUNGURSARI , TASIKMALAYA",
                    "service_display" => "JTR",
                    "service_code" => "JTR18",
                    "goods_type" => "Paket",
                    "currency" => "IDR",
                    "price" => "40000",
                    "etd_from" => "3",
                    "etd_thru" => "4",
                    "times" => "D"
                ],
                [
                    "origin_name" => "JAKARTA",
                    "destination_name" => "BUNGURSARI , TASIKMALAYA",
                    "service_display" => "JTR250",
                    "service_code" => "JTR250",
                    "goods_type" => "Paket",
                    "currency" => "IDR",
                    "price" => "850000",
                    "etd_from" => "3",
                    "etd_thru" => "4",
                    "times" => "D"
                ],
                [
                    "origin_name" => "JAKARTA",
                    "destination_name" => "BUNGURSARI , TASIKMALAYA",
                    "service_display" => "JTR<150",
                    "service_code" => "JTR<150",
                    "goods_type" => "Paket",
                    "currency" => "IDR",
                    "price" => "500000",
                    "etd_from" => "3",
                    "etd_thru" => "4",
                    "times" => "D"
                ],
                [
                    "origin_name" => "JAKARTA",
                    "destination_name" => "BUNGURSARI , TASIKMALAYA",
                    "service_display" => "JTR>250",
                    "service_code" => "JTR>250",
                    "goods_type" => "Paket",
                    "currency" => "IDR",
                    "price" => "1200000",
                    "etd_from" => "3",
                    "etd_thru" => "4",
                    "times" => "D"
                ],
                [
                    "origin_name" => "JAKARTA",
                    "destination_name" => "BUNGURSARI , TASIKMALAYA",
                    "service_display" => "OKE",
                    "service_code" => "OKE19",
                    "goods_type" => "Document/Paket",
                    "currency" => "IDR",
                    "price" => "13000",
                    "etd_from" => "3",
                    "etd_thru" => "6",
                    "times" => "D"
                ],
                [
                    "origin_name" => "JAKARTA",
                    "destination_name" => "BUNGURSARI , TASIKMALAYA",
                    "service_display" => "REG",
                    "service_code" => "REG19",
                    "goods_type" => "Document/Paket",
                    "currency" => "IDR",
                    "price" => "15000",
                    "etd_from" => "2",
                    "etd_thru" => "3",
                    "times" => "D"
                ],
                [
                    "origin_name" => "JAKARTA",
                    "destination_name" => "BUNGURSARI , TASIKMALAYA",
                    "service_display" => "YES",
                    "service_code" => "YES19",
                    "goods_type" => "Document/Paket",
                    "currency" => "IDR",
                    "price" => "24000",
                    "etd_from" => "1",
                    "etd_thru" => "1",
                    "times" => "D"
                ],
            ]
        ];
    }
}
