<?php

declare(strict_types=1);

namespace Costa\Entity\Factory;

use Rakit\Validation\Validator;

class ValidatorFactory
{
    public static function make(array $data, array $rules): ?array
    {
        $validation = (new Validator())->validate($data, $rules);

        if ($validation->fails()) {
            $errors = [];
            foreach ($validation->errors()->all() as $error) {
                $errors[] = $error;
            }

            return $errors;
        }

        return null;
    }
}