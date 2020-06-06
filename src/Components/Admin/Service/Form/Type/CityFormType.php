<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Type;

use App\Entity\City;
use App\Repository\CityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityFormType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Город',
            'class' => City::class,
            'query_builder' => function (CityRepository $cityRepository) {
                return $cityRepository->createQueryBuilder('c')
                    ->where('c.is_active = 1')
                    ->orderBy('c.title', 'DESC');
            },
            'choice_translation_domain' => true,
            'choice_label' => 'title',
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return EntityType::class;
    }
}