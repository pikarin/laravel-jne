<?php

namespace Aditia\Jne\Tests\Unit;

use Aditia\Jne\Tests\TestCase;
use Illuminate\Support\Collection;
use Aditia\Jne\Http\Response\Tracking\Cnote;
use Aditia\Jne\Http\Response\Tracking\Detail;
use Aditia\Jne\Http\Response\Tracking\History;
use Aditia\Jne\Http\Response\TrackingResponse;

class TrackingResponseTest extends TestCase
{
    /** @test */
    public function it_cast_to_tracking_cnote()
    {
        $response = new TrackingResponse([
            'cnote' => $this->cnote(),
        ]);

        tap($response->cnote, function ($cnote) {
            $this->assertInstanceOf(Cnote::class, $cnote);
            $this->assertEquals('010101010101010101', $cnote->cnote_no);
            $this->assertEquals('2019-09-02 23:34:48', $cnote->cnote_date->toDateTimeString());
            $this->assertEquals('2019-09-04 13:46:00', $cnote->cnote_pod_date->toDateTimeString());
            $this->assertEquals("CGK10000", $cnote->cnote_origin);
            $this->assertEquals("PNK21209", $cnote->cnote_destination);
            $this->assertEquals("REG", $cnote->cnote_services_code);
            $this->assertEquals("REG19", $cnote->servicetype);
            $this->assertEquals("11018200", $cnote->cnote_cust_no);
            $this->assertEquals("PAK TATANG", $cnote->cnote_pod_receiver);
            $this->assertEquals("NOVIANTO ERIC SILVESTER", $cnote->cnote_receiver_name);
            $this->assertEquals("SUNGAI RAYA,KUBU RAYA", $cnote->city_name);
            $this->assertEquals("DELIVERED", $cnote->pod_status);
            $this->assertEquals("DELIVERED TO [PAK TATANG | 04-09-2019 13:46 | PONTIANAK ]", $cnote->last_status);
            $this->assertEquals("999", $cnote->cust_type);
            $this->assertEquals("37000", $cnote->cnote_amount);
            $this->assertEquals("1", $cnote->cnote_weight);
            $this->assertEquals("D09", $cnote->pod_code);
            $this->assertEquals("KELUARGA/SAUDARA", $cnote->keterangan);
            $this->assertEquals("1 POLIS NASABAH", $cnote->cnote_goods_descr);
            $this->assertEquals("37000", $cnote->freight_charge);
            $this->assertEquals("37000", $cnote->shippingcost);
            $this->assertEquals("0", $cnote->insuranceamount);
            $this->assertEquals("37000", $cnote->priceperkg);
            $this->assertEquals("https://s3-ap-southeast-1.amazonaws.com/pod.paket.id/S5d.svg", $cnote->signature);
        });
    }

    /** @test */
    public function it_casts_to_tracking_detail()
    {
        $response = new TrackingResponse([
            'detail' => $this->detail(),
        ]);

        tap($response->detail, function ($detail) {
            $this->assertInstanceOf(Detail::class, $detail);
            $this->assertEquals("0166231900105595", $detail->cnote_no);
            $this->assertEquals("2019-09-02 23:34:00", $detail->cnote_date->toDateTimeString());
            $this->assertEquals("1", $detail->cnote_weight);
            $this->assertEquals("JAKARTA", $detail->cnote_origin);
            $this->assertEquals("ASURANSI JIWA SINARMAS MSIG/R", $detail->cnote_shipper_name);
            $this->assertEquals("SINARMAS MSIG TOWER LT. 3A", $detail->cnote_shipper_addr1);
            $this->assertEquals("JL.JEND.SUDIRMAN KAV.21", $detail->cnote_shipper_addr2);
            $this->assertEquals(null, $detail->cnote_shipper_addr3);
            $this->assertEquals("JAKARTA SELATAN", $detail->cnote_shipper_city);
            $this->assertEquals("NOVIANTO ERIC SILVESTER", $detail->cnote_receiver_name);
            $this->assertEquals("JL. ADI SUCIPTOGG. ANGGREK PUT", $detail->cnote_receiver_addr1);
            $this->assertEquals("IH NO.29-BKEC. SUNGAI RAYA KUB", $detail->cnote_receiver_addr2);
            $this->assertEquals("U RAYA 78234 JL. ADI SUCIPTOGG", $detail->cnote_receiver_addr3);
            $this->assertEquals("SUNGAI RAYA,KUBU RAY", $detail->cnote_receiver_city);
        });
    }

    /** @test */
    public function it_casts_to_tracking_detail_collection()
    {
        $response = new TrackingResponse([
            'detail' => $this->detail(),
        ]);

        tap($response->details, function ($details) {
            $this->assertInstanceOf(Collection::class, $details);
            $this->assertInstanceOf(Detail::class, $details->first());
            $this->assertEquals(1, $details->count());
        });
    }

    /** @test */
    public function it_casts_to_history_collection()
    {
        $response = new TrackingResponse([
            'history' => $this->history(),
        ]);

        tap($response->histories, function ($histories) {
            $this->assertInstanceOf(Collection::class, $histories);
            $this->assertInstanceOf(History::class, $histories->first());
            $this->assertEquals(8, $histories->count());
        });

        tap($response->history, function ($history) {
            $this->assertInstanceOf(Collection::class, $history);
            $this->assertInstanceOf(History::class, $history->first());
            $this->assertEquals(8, $history->count());
        });
    }

    /** @test */
    public function it_can_be_initialized_with_error_data()
    {
        $response = new TrackingResponse([
            'error' => '::error message::',
            'status' => false,
        ]);

        $this->assertEquals('::error message::', $response->error);
        $this->assertEquals('::error message::', $response->getErrorMessage());
        $this->assertFalse($response->status);
        $this->assertTrue($response->isError());
    }

    protected function cnote(): array
    {
        return [
            "cnote_no" => "010101010101010101",
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
        ];
    }

    protected function detail(): array
    {
        return [
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
        ];
    }

    public function history(): array
    {
        return [
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
        ];
    }
}
