<?php

namespace Aditia\Jne\Http\Response\Tracking;

use Aditia\Jne\Http\Response\Factory;

class Cnote extends Factory
{
    protected array $dates = [
        'cnote_date',
        'cnote_pod_date'
    ];
}
