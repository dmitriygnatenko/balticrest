<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\DTO\UserDTO;
use App\Components\Admin\Service\Form\ArticleForm;
use App\Components\Admin\Service\Form\UserForm;
use App\Entity\Interfaces\UserRolesInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/user", name="admin.user_list", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function list(Request $request): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $users = array_map(static function ($user) {
            return (new UserDTO())->fillByUser($user);
        }, $users);

        return $this->render('admin/user/list.html.twig', [
            'users' => $users
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
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserForm::class, [], [
            'em' => $entityManager,
            'validation_groups' => ArticleForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $user = (new User())
                ->setIsActive((bool) $formData['is_active'])
                ->setEmail((string) $formData['email'])
                ->setPassword(null)
                ->setRoles([UserRolesInterface::ROLE_USER]);

            try {
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Пользователь успешно добавлен');
            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
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
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserForm::class, $user, [
            'em' => $entityManager,
            'user' => $user,
            'validation_groups' => ArticleForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Пользователь успешно сохранен');
            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
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