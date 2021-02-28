<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\Form\UserForm;
use App\Components\Admin\Service\Mapper\UserDTOMapper;
use App\Entity\Interfaces\UserRolesInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class UserController extends AbstractController
{
    /** @var LoggerInterface */
    private $logger;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/user", name="admin.user_list", methods={"GET"})
     *
     * @param UserDTOMapper $userDTOMapper
     *
     * @return Response
     */
    public function list(UserDTOMapper $userDTOMapper): Response
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/user/list.html.twig', [
            'users' => $userDTOMapper->fillAll($users)
        ]);
    }

    /**
     * @Route("/user/add", name="admin.user_add", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(UserForm::class, [], [
            'validation_groups' => UserForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            try {
                $user = (new User())
                    ->setIsActive((bool) $formData['is_active'])
                    ->setEmail((string) $formData['email'])
                    ->setPassword('')
                    ->setRoles([UserRolesInterface::ROLE_USER]);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('success', 'Пользователь успешно добавлен');
            } catch (Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('danger', 'Ошибка при добавлении пользователя');
            }

            return $this->redirectToRoute('admin.user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Добавление пользователя',
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="admin.user_edit", methods={"GET","POST"})
     *
     * @param User $user
     * @param Request $request
     *
     * @return Response
     */
    public function edit(User $user, Request $request): Response
    {
        $form = $this->createForm(UserForm::class, $user, [
            'user' => $user,
            'validation_groups' => UserForm::VALIDATION_GROUP_UPDATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('success', 'Пользователь успешно сохранен');
            } catch (Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('danger', 'Ошибка при сохранении пользователя');
            }

            return $this->redirectToRoute('admin.user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редактирование пользователя',
        ]);
    }
}