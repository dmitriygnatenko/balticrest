<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\PointType;

class PointFormType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Тип объекта',
            'class' => PointType::class,
            'choice_translation_domain' => true,
            'choice_label' => function ($type) {
                return 'categories.' . $type->getCode();
            },
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