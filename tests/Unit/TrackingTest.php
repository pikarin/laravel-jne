<?php

namespace Aditia\Jne\Tests\Unit;

use Aditia\Jne\Facades\Jne;
use Aditia\Jne\Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Aditia\Jne\Http\Response\Tracking\Cnote;
use Aditia\Jne\Http\Response\TrackingResponse;

class TrackingTest extends TestCase
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
    public function it_send_to_tracking_api_url_with_correct_username_and_key()
    {
        Http::fake([
            '*' => Http::response([
                'cnote' => [],
                'detail' => [],
                'history' => [],
            ]),
        ]);

        Jne::tracking('awb-number');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Content-Type', 'application/x-www-form-urlencoded') &&
                $request->url() == 'https://jne-api-url/tracing/api/list/v1/cnote/awb-number' &&
                $request['username'] == 'jne-api-username' &&
                $request['key'] == 'jne-api-key';
        });
    }

    /** @test */
    public function it_sets_tracking_response_cnote_from_success_response()
    {
        Http::fake([
            '*' => Http::response($this->successResponse()),
        ]);

        $response = Jne::tracking('::awb-number::');

        tap($response->cnote, function ($cnote) {
            $this->assertEquals('XXXXXXXXXXXXXXXX', $cnote->cnote_no);
            $this->assertEquals('CGK10000', $cnote->cnote_origin);
            $this->assertEquals('PNK21209', $cnote->cnote_destination);
            $this->assertEquals('REG', $cnote->cnote_services_code);
            $this->assertEquals('2019-09-04 13:46:00', $cnote->cnote_pod_date->toDateTimeString());
            $this->assertEquals('37000', $cnote->cnote_amount);
            $this->assertEquals('DELIVERED TO [PAK TATANG | 04-09-2019 13:46 | PONTIANAK ]', $cnote->last_status);
        });

        tap($response->detail, function ($detail) {
            $this->assertEquals('0166231900105595', $detail->cnote_no);
        });

        tap($response->history, function ($history) {
            $this->assertEquals('2019-09-02 23:25:00', $history->first()->date->toDateTimeString());
        });
    }

    /** @test */
    public function it_return_correct_response_for_tracking_requests()
    {
        Http::fake([
            '*' => Http::response([
                'error' => 'Cnote No. Not Found.',
                'status' => false,
            ]),
        ]);

        $response = Jne::tracking('::awb-number::');

        $this->assertInstanceOf(TrackingResponse::class, $response);
    }

    protected function successResponse(): array
    {
        return [
            "cnote" => [
                "cnote_no" => "XXXXXXXXXXXXXXXX",
                "cnote_origin" => "CGK10000",
                "cnote_destination" => "PNK21209",
                "cnote_services_code" => "REG",
                "servicetype" => "REG19",
                "cnote_cust_no" => "11018200",
                "cnote_date" => "2019-09-02T23:34:48.000+07:00",
                "cnote_pod_receiver" => "PAK TATANG",
                "cnote_receiver_name" => "NOVIANTO ERIC SILVESTER",
                "city_name" => "SUNGAI RAYA,KUBU RAYA",
                "cnote_pod_date" => "04 SEP 2019  13:46",
                "pod_status" => "DELIVERED",
                "last_status" => "DELIVERED TO [PAK TATANG | 04-09-2019 13:46 | PONTIANAK ]",
                "cust_type" => "999",
                "cnote_amount" => "37000",
                "cnote_weight" => "1",
                "pod_code" => "D09",
                "keterangan" => "KELUARGA/SAUDARA",
                "cnote_goods_descr" => "1 POLIS NASABAH",
                "freight_charge" => "37000",
                "shippingcost" => "37000",
                "insuranceamount" => "0",
                "priceperkg" => "37000",
                "signature" => "https://s3-ap-southeast-1.amazonaws.com/pod.paket.id/S5d.svg"
            ],
            "detail" => [
                [
                    "cnote_no" => "0166231900105595",
                    "cnote_date" => "02-09-2019 23:34",
                    "cnote_weight" => "1",
                    "cnote_origin" => "JAKARTA",
                    "cnote_shipper_name" => "ASURANSI JIWA SINARMAS MSIG/R",
                    "cnote_shipper_addr1" => "SINARMAS MSIG TOWER LT. 3A",
                    "cnote_shipper_addr2" => "JL.JEND.SUDIRMAN KAV.21",
                    "cnote_shipper_addr3" => null,
                    "cnote_shipper_city" => "JAKARTA SELATAN",
                    "cnote_receiver_name" => "NOVIANTO ERIC SILVESTER",
                    "cnote_receiver_addr1" => "JL. ADI SUCIPTOGG. ANGGREK PUT",
                    "cnote_receiver_addr2" => "IH NO.29-BKEC. SUNGAI RAYA KUB",
                    "cnote_receiver_addr3" => "U RAYA 78234 JL. ADI SUCIPTOGG",
                    "cnote_receiver_city" => "SUNGAI RAYA,KUBU RAY"
                ]
            ],
            "history" => [
                [
                    "date" => "02-09-2019 23:25",
                    "desc" => "RECEIVED AT SORTING CENTER [JAKARTA]"
                ],
                [
                    "date" => "02-09-2019 23:34",
                    "desc" => "SHIPMENT RECEIVED BY JNE COUNTER OFFICER AT [JAKARTA]"
                ],
                [
                    "date" => "03-09-2019 03:14",
                    "desc" => "PROCESSED AT SORTING CENTER [JAKARTA]"
                ],
                [
                    "date" => "03-09-2019 05:50",
                    "desc" => "DEPARTED FROM TRANSIT [GATEWAY JAKARTA]"
                ],
                [
                    "date" => "03-09-2019 06:29",
                    "desc" => "RECEIVED AT ORIGIN GATEWAY [GATEWAY JAKARTA]"
                ],
                [
                    "date" => "03-09-2019 21:13",
                    "desc" => "RECEIVED AT WAREHOUSE [PONTIANAK]"
                ],
                [
                    "date" => "04-09-2019 02:23",
                    "desc" => "WITH DELIVERY COURIER [PONTIANAK]"
                ],
                [
                    "date" => "04-09-2019 13:46",
                    "desc" => "DELIVERED TO [PAK TATANG | 04-09-2019 13:46 | PONTIANAK ]"
                ]
            ]
        ];
    }
}
