<?php
    namespace App\Validators;

    use \App\Core\Validator;

    class IpAddressValidator implements Validator {

        public function isValid(string $value): bool {

            if (!filter_var($value, FILTER_VALIDATE_IP)) {
                return false;
            }
            
            return true;
        }

    }