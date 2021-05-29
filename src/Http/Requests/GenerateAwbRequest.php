<?php

namespace Aditia\Jne\Http\Requests;

use Aditia\Jne\Http\Exceptions\InvalidGenerateAwbRequestException;
use Aditia\Jne\Http\Requests\Contracts\Request as RequestContract;

class GenerateAwbRequest implements RequestContract
{
    protected array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function toArray(): array
    {
        return collect($this->attributes)->keyBy(function ($item, $key) {
            $key = strtolower($key);

            if ($key == 'username' || $key == 'api_key') {
                return $key;
            }

            return strtoupper($key);
        })
        ->toArray();
    }

    public function setUsername(string $username): self
    {
        $this->attributes['username'] = $username;

        return $this;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->attributes['api_key'] = $apiKey;

        return $this;
    }

    public function setCredentials(string $username, string $apiKey): self
    {
        return $this->setUsername($username)->setApiKey($apiKey);
    }

    /**
     * Validate the request body.
     *
     * @return void
     *
     * @throws \Aditia\Jne\Http\Exceptions\InvalidGenerateAwbRequestException
     */
    public function validate(): void
    {
        $requiredParams = [
            'username',
            'api_key',
            'OLSHOP_BRANCH',
            'OLSHOP_CUST',
            'OLSHOP_ORDERID',
            'OLSHOP_SHIPPER_NAME',
            'OLSHOP_SHIPPER_ADDR1',
            'OLSHOP_SHIPPER_ADDR2',
            'OLSHOP_SHIPPER_ADDR3',
            'OLSHOP_SHIPPER_CITY',
            'OLSHOP_SHIPPER_ZIP',
            'OLSHOP_SHIPPER_PHONE',
            'OLSHOP_RECEIVER_NAME',
            'OLSHOP_RECEIVER_ADDR1',
            'OLSHOP_RECEIVER_ADDR2',
            'OLSHOP_RECEIVER_CITY',
            'OLSHOP_RECEIVER_ZIP',
            'OLSHOP_RECEIVER_PHONE',
            'OLSHOP_QTY',
            'OLSHOP_WEIGHT',
            'OLSHOP_GOODSDESC',
            'OLSHOP_GOODSVALUE',
            'OLSHOP_GOODSTYPE',
            'OLSHOP_INS_FLAG',
            'OLSHOP_ORIG',
            'OLSHOP_DEST',
            'OLSHOP_SERVICE',
            'OLSHOP_COD_FLAG',
            'OLSHOP_COD_AMOUNT',
        ];

        foreach ($requiredParams as $param) {
            if (! ($this->attributes[$param] ?? null)) {
                throw new InvalidGenerateAwbRequestException("$param is required.");
            }
        }

        return;
    }
}
