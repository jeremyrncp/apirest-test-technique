<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Utils\Symfony;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;

class FormUtils
{
    public const DELIMITER = ',';

    /**
     * @param FormErrorIterator $formErrors
     * @param string $delimiter
     *
     * @return string
     */
    public static function errorsToString(FormErrorIterator $formErrors, string $delimiter = self::DELIMITER)
    {
        $errors = [];

        /** @var FormError $formError */
        foreach ($formErrors as $formError) {
            $errors[] = $formError->getMessage();
        }

        return implode($delimiter, $errors);
    }
}
