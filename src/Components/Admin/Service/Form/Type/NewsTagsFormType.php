<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Interfaces\NewsTagsInterface as Tags;

class NewsTagsFormType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Тэги',
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'Концерты, праздники' => Tags::TAG_EVENTS,
                'Как добраться' => Tags::TAG_TRANSPORT,
                'Акции' => Tags::TAG_OFFERS,
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