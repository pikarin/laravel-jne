<?php

namespace Aditia\Jne\Http\Response;

use Aditia\Jne\Http\Response\Tariff\Price;
use Aditia\Jne\Http\Response\Factory as ResponseFactory;
use Illuminate\Support\Collection;

class TariffResponse extends ResponseFactory
{
    public function price($value): Collection
    {
        return (new Collection($value))->map(fn ($price) => new Price($price));
    }

    public function prices(): Collection
    {
        return $this->price($this->attributes['price'] ?? []);
    }

    public function isError(): bool
    {
        return (bool) $this->error;
    }

   public function getErrorMessage(): ?string
   {
       return $this->error;
   }
}
