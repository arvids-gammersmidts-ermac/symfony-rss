<?php

namespace App\Http\Validator;

interface ValidatorInterface
{
    public function validate(): bool;

    public function getErrors(): array;
}
