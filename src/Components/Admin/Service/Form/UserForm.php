<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form;

use App\Components\Admin\Service\Form\Type\IsActiveFormType;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserForm extends AbstractType
{
    /** @var string */
    public const VALIDATION_GROUP_CREATE = 'create';

    /** @var string */
    public const VALIDATION_GROUP_UPDATE = 'update';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @throws HttpException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityManager $em */
        $em = $options['em'];

        /** @var User|null $user */
        $user = $options['user'] ?? null;

        $builder->setRequired(false);

        $builder->add('is_active', IsActiveFormType::class);

        $builder->add('email', TextType::class, [
            'label' => 'Адрес электронной почты',
            'constraints' => [
                new Callback([
                    'groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE],
                    'callback' => static function($value, $context) use ($em, $user) {
                        if ($value === '' || $value === null) {
                            return;
                        }

                        if ($user !== null && $user->getEmail() === $value) {
                            return;
                        }

                        if ($em->getRepository(User::class)->count(['email' => $value])) {
                            $context->buildViolation('Данный адрес почты уже используется')
                                ->atPath('email')
                                ->addViolation();
                        }
                    }
                ]),
                new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]])
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
            ->addAllowedTypes('em', EntityManagerInterface::class)
            ->setDefault('user', null)
            ->setRequired('validation_groups');
    }
}