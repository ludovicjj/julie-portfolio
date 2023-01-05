<?php

namespace App\Handler\Validation;

use App\Exception\ValidatorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorsValidationHandler
{
    /**
     * @throws ValidatorException
     */
    public static function handleErrors(ConstraintViolationListInterface $constraintViolationList)
    {
        if (count($constraintViolationList) > 0) {
            $errors = [];
            /** @var ConstraintViolationInterface $constraint */
            foreach ($constraintViolationList as $constraint) {
                $errors[] = [
                    'property' => $constraint->getPropertyPath(),
                    'message' => $constraint->getMessage()
                ];
            }

            throw new ValidatorException(
                $errors,
                'unprocessable entity',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}