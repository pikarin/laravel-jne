<?php

namespace Aditia\Jne\Http\Requests;

use Aditia\Jne\Http\Exceptions\InvalidTariffRequestException;
use Aditia\Jne\Http\Requests\Contracts\Request as RequestContract;

class TariffRequest implements RequestContract
{
    protected array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function toArray(): array
    {
        return $this->attributes;
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
     * @throws InvalidTariffRequestException
     */
    public function validate(): void
    {
        if (! ($this->attributes['username'] ?? null)) {
            throw new InvalidTariffRequestException('username is required.');
        }

        if (! ($this->attributes['api_key'] ?? null)) {
            throw new InvalidTariffRequestException('api_key is required.');
        }

        if (! ($this->attributes['from'] ?? null)) {
            throw new InvalidTariffRequestException('from is required.');
        }

        if (! ($this->attributes['thru'] ?? null)) {
            throw new InvalidTariffRequestException('thru is required.');
        }

        if (! ($this->attributes['weight'] ?? null)) {
            throw new InvalidTariffRequestException('weight is required.');
        }

        if (! is_numeric($this->attributes['weight'])) {
            throw new InvalidTariffRequestException('weight must be a number.');
        }

        return;
    }
}
