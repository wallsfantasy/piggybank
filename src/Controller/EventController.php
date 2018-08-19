<?php
declare(strict_types = 1);

namespace App\Controller;

use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\EventBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EventController
{
    private const EVENT_CLASS_ATTRIBUTE = 'event_class';

    /** @var EventBus */
    private $eventBus;

    /** @var MessageFactory */
    private $messageFactory;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(EventBus $eventBus, MessageFactory $messageFactory, LoggerInterface $logger)
    {
        $this->eventBus = $eventBus;
        $this->messageFactory = $messageFactory;
        $this->logger = $logger;
    }

    public function postAction(Request $request)
    {
        $eventClass = $request->attributes->get(self::EVENT_CLASS_ATTRIBUTE);
        if (null === $eventClass) {
            return $this->responseEventAttributeNotFound();
        }

        $payload = \json_decode($request->getContent(), true);
        if (null === $payload) {
            return $this->responseInvalidJson($request->getContent());
        }

        $event = $this->messageFactory->createMessageFromArray($eventClass, ['payload' => $payload]);

        try {
            $this->eventBus->dispatch($event);
        } catch (CommandDispatchException $e) {
            return $this->responseThrowableMessage($e->getPrevious());
        } catch (\Throwable $e) {
            return $this->responseThrowableMessage($e);
        }

        return JsonResponse::create(null, Response::HTTP_ACCEPTED);
    }

    private function responseEventAttributeNotFound(): JsonResponse
    {
        return JsonResponse::create(
            [
                'message' => \sprintf(
                    'Event class ("%s") was not configured.',
                    self::EVENT_CLASS_ATTRIBUTE
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
