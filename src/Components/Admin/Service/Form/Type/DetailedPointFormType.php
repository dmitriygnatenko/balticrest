<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Type;

use App\Entity\Interfaces\DetailedTypesInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailedPointFormType extends AbstractType implements DetailedTypesInterface
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Подтип объекта',
            'choices' => [
                '' => '',
                'Бар' => self::DETAILED_TYPE_BAR,
                'Пиццерия' => self::DETAILED_TYPE_PIZZA,
                'Ресторан' => self::DETAILED_TYPE_RESTAURANT,
                'Парковка' => self::DETAILED_TYPE_PARKING,
                'Бесплатная парковка' => self::DETAILED_TYPE_FREE_PARKING,
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}