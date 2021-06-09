<?php

namespace Aditia\Jne\Http\Response\Tracking;

use Aditia\Jne\Http\Response\Factory;

/**
 * @property string|null $cnote_no
 * @property string|null $cnote_origin
 * @property string|null $cnote_destination
 * @property string|null $cnote_services_code
 * @property string|null $servicetype
 * @property string|null $cnote_cust_no
 * @property \Carbon\Carbon|null $cnote_date
 * @property string|null $cnote_pod_receiver
 * @property string|null $cnote_receiver_name
 * @property string|null $city_name
 * @property \Carbon\Carbon|null $cnote_pod_date
 * @property string|null $pod_status
 * @property string|null $last_status
 * @property string|null $cust_type
 * @property string|null $cnote_amount
 * @property string|null $cnote_weight
 * @property string|null $pod_code
 * @property string|null $keterangan
 * @property string|null $cnote_goods_descr
 * @property string|null $freight_charge
 * @property string|null $shippingcost
 * @property string|null $insuranceamount
 * @property string|null $priceperkg
 * @property string|null $signature
 */
class Cnote extends Factory
{
    protected array $dates = [
        'cnote_date',
        'cnote_pod_date'
    ];
}
