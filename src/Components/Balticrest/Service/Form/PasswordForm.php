<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'constraints' => array(
                new NotBlank(['message' => 'password.passwords_blank']),
            ),
            'invalid_message' => 'password.passwords_not_equal',
            'options' => ['attr' => ['class' => 'password-field']],
            'first_options'  => ['label' => 'confirm.form.label.password'],
            'second_options' => ['label' => 'confirm.form.label.password_repeat'],
        ]);
    }
}