<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Form;

use App\DTO\MovieOmdbDTO;
use Symfony\Component\Form\AbstractType;

class MovieOmdbType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        return $builder
                    ->add('imdbID')
                    ->add('Title')
                    ->add('Poster');
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MovieOmdbDTO::class,
            'allow_extra_fields' => true
        ]);
    }
}
