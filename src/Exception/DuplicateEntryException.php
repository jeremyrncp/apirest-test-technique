<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class DuplicateEntryException extends HttpException
{
    public function __construct(int $statusCode, string $message = null, \Exception $previous = null, array $headers = array(), ?int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
