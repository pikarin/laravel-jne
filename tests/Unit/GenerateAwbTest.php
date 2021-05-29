<?php

namespace Aditia\Jne\Tests\Unit;

use Aditia\Jne\Facades\Jne;
use Aditia\Jne\Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Aditia\Jne\Http\Requests\GenerateAwbRequest;
use Aditia\Jne\Http\Response\GenerateAwbResponse;
use Aditia\Jne\Http\Exceptions\InvalidGenerateAwbRequestException;

class GenerateAwbTest extends TestCase
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
    public function it_send_to_generate_awb_api_correctly()
    {
        Http::fake([
            '*' => Http::response($this->successResponse()),
        ]);

        Jne::generateAwb($this->requestBody());

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Content-Type', 'application/x-www-form-urlencoded') &&
                $request->url() == 'https://jne-api-url/tracing/api/generatecnote' &&
                $request['username'] == 'jne-api-username' &&
                $request['api_key'] == 'jne-api-key';
        });
    }

    /** @test */
    public function it_sets_generate_awb_response_from_success_response()
    {
        Http::fake([
            '*' => Http::response($this->successResponse()),
        ]);

        $response = Jne::generateAwb($this->requestBody());

        $this->assertEquals('0109401900003724', $response->awb->airwaybill);
        $this->assertEquals('0109401900003724', $response->detail->cnote_no);
    }

    /** @test */
    public function it_validates_before_sending_generate_awb_request()
    {
        $this->expectException(InvalidGenerateAwbRequestException::class);

        Http::fake([
            '*' => Http::response($this->successResponse()),
        ]);

        Jne::generateAwb(new GenerateAwbRequest([]));

        Http::assertNothingSent();
    }

    /** @test */
    public function it_return_correct_response_for_generate_awb_requests()
    {
        Http::fake([
            '*' => Http::response([
                'error' => "Username And API KEY Must Be Sent.",
                'status' => "false",
            ]),
        ]);

        $response = Jne::generateAwb($this->requestBody());

        $this->assertInstanceOf(GenerateAwbResponse::class, $response);
    }

    protected function requestBody(): GenerateAwbRequest
    {
        return new GenerateAwbRequest([
            'OLSHOP_BRANCH' => 'branch',
            'OLSHOP_CUST' => 'cust',
            'OLSHOP_ORDERID' => 'orderid',
            'OLSHOP_SHIPPER_NAME' => 'name',
            'OLSHOP_SHIPPER_ADDR1' => 'addr1',
            'OLSHOP_SHIPPER_ADDR2' => 'addr2',
            'OLSHOP_SHIPPER_ADDR3' => 'addr3',
            'OLSHOP_SHIPPER_CITY' => 'city',
            'OLSHOP_SHIPPER_ZIP' => '12345',
            'OLSHOP_SHIPPER_PHONE' => '088456329172',
            'OLSHOP_RECEIVER_NAME' => 'rname',
            'OLSHOP_RECEIVER_ADDR1' => 'raddr1',
            'OLSHOP_RECEIVER_ADDR2' => 'raddr2',
            'OLSHOP_RECEIVER_CITY' => 'raddr3',
            'OLSHOP_RECEIVER_ZIP' => '12345',
            'OLSHOP_RECEIVER_PHONE' => '088426323172',
            'OLSHOP_QTY' => 1,
            'OLSHOP_WEIGHT' => 1,
            'OLSHOP_GOODSDESC' => 'desc',
            'OLSHOP_GOODSVALUE' => 'value',
            'OLSHOP_GOODSTYPE' => 'type',
            'OLSHOP_INS_FLAG' => 'flag',
            'OLSHOP_ORIG' => 'orig',
            'OLSHOP_DEST' => 'dest',
            'OLSHOP_SERVICE' => 'service',
            'OLSHOP_COD_FLAG' => 'cod',
            'OLSHOP_COD_AMOUNT' => 1000,
        ]);
    }

    protected function successResponse(): array
    {
        return [
            "detail" => [
                [
                    "status" => "sukses",
                    "cnote_no" => "0109401900003724"
                ],
            ],
        ];
    }
}
