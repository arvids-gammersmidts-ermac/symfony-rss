<?php

namespace App\Http\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class EmailCheckValidator extends AbstractValidator
{
    public function __construct($data)
    {
        parent::__construct($data);
    }

    protected function getConstraints()
    {
        return new Assert\Collection(array(
            'email' => new Assert\Email(),
        ));
    }
}
