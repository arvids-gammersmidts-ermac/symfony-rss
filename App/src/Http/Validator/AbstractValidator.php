<?php

namespace App\Http\Validator;

use stdClass;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

abstract class AbstractValidator implements ValidatorInterface
{
    protected $data;

    protected $errors;

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function __construct($data)
    {
        $this->data = $data;
        $this->errors = [];
    }

    public function validate(): bool
    {
        $result = true;
        $validator = Validation::createValidator();
        $orderViolations = $validator->validate($this->data, $this->getConstraints());
        if ($orderViolations->count() > 0) {
            $this->errors = $this->getFormattedErrors($orderViolations);
            $result = false;
        }

        return $result;
    }

    private function getFormattedErrors($violations): array
    {
        $errors = [];
        foreach ($violations as $violation){
            /** @var ConstraintViolation $violation */
            $error = new stdClass();
            $error->field = $violation->getPropertyPath();
            $error->value = $violation->getInvalidValue();
            $error->message = $violation->getMessage();
            $errors[] = $error;
        }
        return $errors;
    }
}
