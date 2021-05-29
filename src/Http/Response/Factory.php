<?php

namespace Aditia\Jne\Http\Response;

use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class Factory
{
    use Concern\HasAttributes;

    protected int $status;

    protected array $headers;

    public function __construct(array $attributes, int $status = 200, array $headers = [])
    {
        $this->attributes = $attributes;
        $this->status = $status;
        $this->headers = $headers;
    }

    /**
     * Get the JSON decoded body of the response as an array.
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Get the JSON decoded body of the response as an array.
     */
    public function toCollection(): Collection
    {
        return new Collection($this->data);
    }

    public function isError(): bool
    {
        return false;
    }

    public function getErrorMessage(): ?string
    {
        return null;
    }

    public function __get($name)
    {
        return $this->getAttribute($name);
    }
}
