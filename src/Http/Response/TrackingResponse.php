<?php

namespace Aditia\Jne\Http\Response;

use Illuminate\Support\Collection;
use Aditia\Jne\Http\Response\Tracking\Cnote;
use Aditia\Jne\Http\Response\Tracking\Detail;
use Aditia\Jne\Http\Response\Tracking\History;
use Aditia\Jne\Http\Response\Factory as ResponseFactory;

class TrackingResponse extends ResponseFactory
{
    public function cnote($value): Cnote
    {
        return new Cnote($value);
    }

    public function detail($value): Detail
    {
        return new Detail($value[0] ?? $value);
    }

    public function details(): Collection
    {
        $details = $this->attributes['detail'] ?? [];

        return (new Collection($details))->map(fn ($detail) => $this->detail($detail));
    }

    public function history($value): Collection
    {
        return (new Collection($value))->map(fn ($history) => new History($history));
    }

    public function histories(): Collection
    {
        return $this->history($this->attributes['history'] ?? []);
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
