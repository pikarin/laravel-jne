<?php

namespace Aditia\Jne\Http\Response\AWB;

use Aditia\Jne\Http\Response\Factory;

/**
 * @property string|null $awb
 * @property string|null $airwaybill
 */
class Awb extends Factory
{
    public function awb()
    {
        return $this->airwaybill ?? $this->cnote_no;
    }

    public function airwaybill()
    {
        return $this->attributes['airwaybill'] ?? $this->awb;
    }
}
