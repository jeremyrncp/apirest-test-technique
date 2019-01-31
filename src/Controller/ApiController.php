<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Utils\Symfony\FormUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiController extends AbstractController
{
    public const VALID_CONTENT_TYPE = ['application/json'];

    /**
     * @param Request $request
     * @throws BadRequestHttpException
     */
    public function isValidBody(Request $request)
    {
        if (!$request->headers->has('Content-Type')) {
            throw new BadRequestHttpException('Content-Type must be defined');
        }

        if (!in_array($request->headers->get('Content-Type'), self::VALID_CONTENT_TYPE, true)) {
            throw new BadRequestHttpException(
                sprintf('Content-type isn\'t accepted, formats accepted %s', implode(',', self::VALID_CONTENT_TYPE))
            );
        }
        if (!is_array(json_decode($request->getContent(), true))) {
            throw new BadRequestHttpException('Your JSON isn\'t valid');
        }
    }

    protected function getResponseWithFormErrors(FormErrorIterator $formErrorIterator): Response
    {
        return new Response(
            json_encode(
                ['msg' => FormUtils::errorsToString($formErrorIterator)]
            ),
            Response::HTTP_BAD_REQUEST
        );
    }
}
