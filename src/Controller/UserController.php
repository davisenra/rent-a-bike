<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    ) {}
}