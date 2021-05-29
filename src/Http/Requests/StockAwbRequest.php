<?php

namespace Aditia\Jne\Http\Requests;

use Aditia\Jne\Http\Exceptions\InvalidStockAwbRequestException;
use Aditia\Jne\Http\Requests\Contracts\Request as RequestContract;

class StockAwbRequest implements RequestContract
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
     * @throws \Aditia\Jne\Http\Exceptions\InvalidStockAwbRequestException
     */
    public function validate(): void
    {
        $requiredParams = [
            'username',
            'api_key',
            'BRANCH',
            'CUST_ID',
            'CREATE_BY',
            'REQUEST_AWB',
            'REQUEST_BY',
            'REQUEST_NO',
            'REASON',
        ];

        foreach ($requiredParams as $param) {
            if (! ($this->attributes[$param] ?? null)) {
                throw new InvalidStockAwbRequestException("$param is required.");
            }
        }

        return;
    }
}
