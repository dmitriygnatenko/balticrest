<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, [
            'label' => 'registration.form.label.username',
            'constraints' => [
                new NotBlank(['message' => 'registration.username_blank']),
            ]
        ]);

        $builder->add('email', TextType::class, [
            'label' => 'registration.form.label.email',
            'constraints' => [
                new NotBlank(['message' => 'registration.email_blank']),
                new Email(['message' => 'registration.email_incorrect'])
            ]
        ]);
    }
}