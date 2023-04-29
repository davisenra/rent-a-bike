<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class CustomRequest implements CustomRequestInterface
{
    private array $validationErrors = [];

    public function __construct(private ValidatorInterface $validator)
    {
        $this->populate();
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function validate(): bool
    {
        $errors = $this->validator->validate($this);

        /** @var ConstraintViolation $message  */
        foreach ($errors as $message) {
            $this->validationErrors[] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        $isValid = $this->validationErrors === [];

        if ($isValid) {
            unset($this->validator);
            unset($this->validationErrors);

            return true;
        }

        return false;
    }

    protected function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    protected function populate(): void
    {
        foreach ($this->getRequest()->toArray() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}