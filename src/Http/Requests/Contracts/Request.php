<?php

namespace Aditia\Jne\Http\Requests\Contracts;

interface Request
{
    public function toArray(): array;

    public function validate(): void;

    public function setUsername(string $username): self;

    public function setApiKey(string $apiKey): self;

    public function setCredentials(string $username, string $apiKey): self;
}
