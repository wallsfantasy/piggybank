<?php
declare(strict_types = 1);

namespace App\Controller;

use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CommandController
{
    private const COMMAND_CLASS_ATTRIBUTE = 'command_class';

    /** @var CommandBus */
    private $commandBus;

    /** @var MessageFactory */
    private $messageFactory;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(CommandBus $commandBus, MessageFactory $messageFactory, LoggerInterface $logger)
    {
        $this->commandBus = $commandBus;
        $this->messageFactory = $messageFactory;
        $this->logger = $logger;
    }

    public function postAction(Request $request)
    {
        $commandClass = $request->attributes->get(self::COMMAND_CLASS_ATTRIBUTE);
        if (null === $commandClass) {
            return $this->responseCommandAttributeNotFound();
        }

        $payload = \json_decode($request->getContent(), true);
        if (null === $payload) {
            return $this->responseInvalidJson($request->getContent());
        }

        $command = $this->messageFactory->createMessageFromArray($commandClass, ['payload' => $payload]);

        try {
            $this->commandBus->dispatch($command);
        } catch (CommandDispatchException $e) {
            return $this->responseThrowableMessage($e->getPrevious());
        } catch (\Throwable $e) {
            return $this->responseThrowableMessage($e);
        }

        return JsonResponse::create(null, Response::HTTP_ACCEPTED);
    }

    private function responseCommandAttributeNotFound(): JsonResponse
    {
        return JsonResponse::create(
            [
                'message' => \sprintf(
                    'Command class ("%s") was not configured.',
                    self::COMMAND_CLASS_ATTRIBUTE
                )
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    private function responseInvalidJson(string $raw): JsonResponse
    {
        return JsonResponse::create(
            [
                'message' => \sprintf(
                    'Invalid JSON ("%s").',
                    $raw
                )
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    private function responseThrowableMessage(\Throwable $e, int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $this->logger->error($e);
        return JsonResponse::create(
            [
                'message' => $e->getMessage()
            ],
            $httpCode
        );
    }
}
