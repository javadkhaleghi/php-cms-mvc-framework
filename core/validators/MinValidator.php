<?php

namespace Core\Validators;

use Core\Validators\Validator;

class MinValidator extends Validator
{
    public function runValidation()
    {
        $value = $this->_object->{$this->field};
        
        return strlen($value) >= $this->rule;
    }
}