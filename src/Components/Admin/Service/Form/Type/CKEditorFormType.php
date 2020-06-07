<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Type;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CKEditorFormType extends AbstractType
{

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'config' => [
                'toolbar' => 'my_standart'
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return CKEditorType::class;
    }
}