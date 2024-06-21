<?php

namespace App\EventSubscriber;

use App\Service\LogService;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private LogService $logService)
    {
    }
    
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        $className = (new \ReflectionClass($exception))->getShortName();
        $statusCode = ($exception instanceof HttpException) ? $exception->getStatusCode() : 500;
        $trace = $exception->getTraceAsString();
        $message = [
            '#message: ' . $exception->getMessage(),
            '#url: ' . $request->getUri(),
            '#referer: ' . $request->headers->get('referer'),
            '#user-agent: ' . $request->headers->get('user-agent'),
        ];

        $this->logService->error("Error $statusCode", join("\n", $message), $className, $trace);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
