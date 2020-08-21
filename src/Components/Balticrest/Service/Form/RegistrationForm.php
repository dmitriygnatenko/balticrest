<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityManager $em */
        $em = $options['em'];

        $builder->add('username', TextType::class, [
            'label' => 'registration.form.label.username',
            'constraints' => [
                new NotBlank(['message' => 'username.username_blank']),
            ]
        ]);

        $builder->add('email', TextType::class, [
            'label' => 'registration.form.label.email',
            'constraints' => [
                new NotBlank(['message' => 'email.email_blank']),
                new Email(['message' => 'email.email_incorrect']),
                new Callback([
                    'callback' => function($value, $context) use ($em) {
                        if ($value === '' || $value === null) {
                            return;
                        }

                        if ($em->getRepository(User::class)->count(['email' => $value])) {
                            $context->buildViolation('email.email_unique')
                                ->atPath('email')
                                ->addViolation();
                        }
                    }
                ])
            ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('em')
            ->addAllowedTypes('em', EntityManagerInterface::class);
    }
}