<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Enum\TransactionStatus;
use App\Enum\TransactionType;
use App\Request\WalletDepositRequest;
use App\Service\TransactionService;
use App\Service\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class TransactionController extends AbstractController
{
    public function __construct(
        private readonly TransactionService $transactionService,
        private readonly WalletService $walletService,
    ) {}

    #[Route('/transactions/deposit', name: 'deposit', methods: ['POST'])]
    public function newDeposit(WalletDepositRequest $depositRequest): JsonResponse
    {
        if (!$depositRequest->validate()) {
            return $this->json($depositRequest->getValidationErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var User $user */
        $user = $this->getUser();
        $userWallet = $user->getWallet();

        $transaction = $this->transactionService->createDepositTransaction([
            'amount' => $depositRequest->amount,
        ], $userWallet);

        $this->walletService->addFundsToWallet($transaction, $userWallet);

        return $this->json(['message' => 'Deposit successful!'], Response::HTTP_CREATED);
    }
}