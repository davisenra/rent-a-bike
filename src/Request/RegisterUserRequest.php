<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterUserRequest extends CustomRequest
{
    #[Assert\NotBlank()]
    #[Assert\Email()]
    public string $email;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 8, max: 32)]
    public string $password;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 32)]
    #[Assert\Regex(pattern: '/^[a-zA-Z]+$/')]
    public string $firstName;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 32)]
    #[Assert\Regex(pattern: '/^[a-zA-Z]+$/')]
    public string $lastName;
}