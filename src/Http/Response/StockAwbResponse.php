<?php

namespace Aditia\Jne\Http\Response;

use Aditia\Jne\Http\Response\AWB\Awb;
use Aditia\Jne\Http\Response\Factory as ResponseFactory;
use Illuminate\Support\Collection;

class StockAwbResponse extends ResponseFactory
{
    public function awb($value): Collection
    {
        return (new Collection($value))->map(fn ($awb) => new Awb($awb));
    }

    public function isError(): bool
    {
        return (bool) $this->error || $this->status == 'Error';
    }

   public function getErrorMessage(): ?string
   {
       return $this->reason ?? $this->error;
   }
}
