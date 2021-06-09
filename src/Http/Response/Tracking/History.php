<?php

namespace Aditia\Jne\Http\Response\Tracking;

use Aditia\Jne\Http\Response\Factory;

/**
 * @property \Carbon\Carbon|null $dates
 * @property string|null $desc
 */
class History extends Factory
{
    protected array $dates = [
        'date',
    ];
}
