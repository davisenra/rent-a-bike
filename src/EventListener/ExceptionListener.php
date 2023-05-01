<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $isJsonException = get_class($exception) === 'JsonException';

        if ($isJsonException) {
            $response = new JsonResponse([
                'message' => $exception->getMessage(),
            ], $exception->getCode());

            $event->setResponse($response);
        }
    }
}
