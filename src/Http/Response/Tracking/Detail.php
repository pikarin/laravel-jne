<?php

namespace Aditia\Jne\Http\Response\Tracking;

use Aditia\Jne\Http\Response\Factory;

/**
 * @property string|null $cnote_no
 * @property \Carbon\Carbon|null $cnote_date
 * @property string|null $cnote_weight
 * @property string|null $cnote_origin
 * @property string|null $cnote_shipper_name
 * @property string|null $cnote_shipper_addr1
 * @property string|null $cnote_shipper_addr2
 * @property string|null $cnote_shipper_addr3
 * @property string|null $cnote_shipper_city
 * @property string|null $cnote_receiver_name
 * @property string|null $cnote_receiver_addr1
 * @property string|null $cnote_receiver_addr2
 * @property string|null $cnote_receiver_addr3
 * @property string|null $cnote_receiver_city
 */
class Detail extends Factory
{
    protected array $dates = [
        'cnote_date',
    ];
}
