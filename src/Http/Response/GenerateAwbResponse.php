<?php

namespace Aditia\Jne\Http\Response;

use Aditia\Jne\Http\Response\AWB\Awb;
use Aditia\Jne\Http\Response\Factory as ResponseFactory;

class GenerateAwbResponse extends ResponseFactory
{
    public function detail($value)
    {
        return new Awb($value[0] ?? []);
    }

    public function awb()
    {
        return $this->detail($this->attributes['detail']);
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
