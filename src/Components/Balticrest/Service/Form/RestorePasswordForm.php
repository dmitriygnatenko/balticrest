<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RestorePasswordForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, [
            'label' => 'restore.form.label.email',
            'constraints' => [
                new NotBlank(['message' => 'email.email_blank']),
                new Email(['message' => 'email.email_incorrect']),
            ]
        ]);
    }
}