<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Type;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;

class CKEditorFormType extends AbstractType
{
    /**
     * @return string
     */
    public function getParent()
    {
        return CKEditorType::class;
    }
}