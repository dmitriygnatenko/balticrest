<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Auth\UserConfirmServiceInterface;
use App\Components\Balticrest\Service\Form\PasswordForm;
use App\Components\Balticrest\Service\Form\RestorePasswordForm;
use App\Components\Balticrest\Service\Mail\EmailSenderInterface;
use App\Entity\User;
use App\Entity\UserConfirmCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use LogicException;

class UserController extends AbstractController
{
    use TargetPathTrait;

    /**
     * Страница авторизации
     *
     * @Route("/login", name="login")
     *
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
              return $this->redirectToRoute('main');
        }

        if ($request->isMethod('GET')) {
            $referer = $request->headers->get('referer');
            if ($referer !== null && $referer !== $request->getUri()) {
                $this->saveTargetPath($request->getSession(), 'main', $referer);
            }
        }

        return $this->render('balticrest/user/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * Страница восстановления пароля
     *
     * @Route("/restore", name="restore")
     *
     * @param Request $request
     * @param EmailSenderInterface $emailSender
     *
     * @return Response
     */
    public function restore(Request $request, EmailSenderInterface $emailSender): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        $form = $this->createForm(RestorePasswordForm::class);

        $form->handleRequest($request);

        $sent = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $formData['email'] ?? '']);

            if ($user !== null && $user->getIsActive()) {
                $emailSender->sendRestoreConfirmEmail($user);
            }

            $sent = true;
        }

        return $this->render(
            'balticrest/user/restore.html.twig',
            [
                'form' => $form->createView(),
                'sent' => $sent
            ]
        );
    }

    /**
     * Страница установки нового пароля при сбросе
     *
     * @Route(
     *     "/restore/confirm/{code}",
     *      name="restore_confirm",
     *      requirements={"code"="[0-9a-z]{40}"}
     * )
     *
     * @param string $code
     * @param Request $request
     * @param UserConfirmServiceInterface $userConfirmService
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     *
     * @return Response
     */
    public function restoreConfirm(
        string $code,
        Request $request,
        UserConfirmServiceInterface $userConfirmService,
        UserPasswordEncoderInterface $userPasswordEncoder
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        $template = 'balticrest/user/restore_confirm.html.twig';

        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        $userConfirmCode = $userConfirmService->getUserConfirmCode($code, UserConfirmCode::TYPE_RESTORE_PASSWORD);

        if ($userConfirmCode === null) {
            return $this->render($template, ['confirm_code_not_found' => true]);
        }

        if ($userConfirmService->validateIsNotExpired($userConfirmCode) === false) {
            return $this->render($template, ['confirm_code_expired' => true]);
        }

        $user = $userConfirmCode->getUser();

        if ($user === null) {
            return $this->render($template, ['confirm_code_not_found' => true]);
        }

        $form = $this->createForm(PasswordForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $user->setPassword($userPasswordEncoder->encodePassword($user, $formData['password']));

            $entityManager->persist($user);
            $entityManager->remove($userConfirmCode);
            $entityManager->flush();

            return $this->render($template, ['password_successfully_set' => true]);
        }

        return $this->render($template, ['form' => $form->createView()]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank.');
    }
}
