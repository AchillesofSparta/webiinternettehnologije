<?php
    namespace App\Validators;

    use \App\Core\Validator;

    class EmailValidator implements Validator {

        public function isValid(string $value): bool {

            if (!filter_var($valie, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            
            return true;
        }

    }