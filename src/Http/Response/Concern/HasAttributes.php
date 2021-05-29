<?php

namespace Aditia\Jne\Http\Response\Concern;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait HasAttributes
{
    protected array $attributes = [];

    protected array $dates = [];

    protected function asDateTime($value): Carbon
    {
        return Carbon::parse($value);
    }

    /**
     * @param  string  $name
     * @return mixed
     */
    protected function getAttribute(string $name)
    {
        $value = $this->attributes[$name] ?? null;

        if ($value !== null && in_array($name, $this->dates)) {
            $value = $this->asDateTime($value);
        }

        if (method_exists($this, $camelName = Str::camel($name))) {
            $value = ($value !== null) ? $this->{$camelName}($value) : $this->{$camelName}();
        }

        return $value;
    }
}
