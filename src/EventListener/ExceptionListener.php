<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if($event->getException() instanceof HttpException) {
            /** @var HttpException $exception */
            $exception = $event->getException();
            $response = new Response(
                json_encode(
                    ['msg' => $exception->getMessage()]
                ),
                $exception->getStatusCode()
            );
            $event->setResponse($response);
        }
    }
}
