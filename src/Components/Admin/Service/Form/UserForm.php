<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form;

use App\Components\Admin\Service\Form\Type\IsActiveFormType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserForm extends AbstractForm
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @throws HttpException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User|null $user */
        $user = $options['user'] ?? null;
        $em = $this->entityManager;

        $allGroups = [
            self::VALIDATION_GROUP_CREATE,
            self::VALIDATION_GROUP_UPDATE
        ];


        $builder->setRequired(false);

        $builder->add('is_active', IsActiveFormType::class);

        $builder->add('email', TextType::class, [
            'label' => 'Адрес электронной почты',
            'constraints' => [
                new NotBlank(['groups' => $allGroups]),
                new Email(['groups' => $allGroups]),
                new Callback([
                    'groups' => $allGroups,
                    'callback' => static function ($value, $context) use ($em, $user) {
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
            ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('user', null)
            ->setRequired('validation_groups');
    }
}