<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Event\UserCreatedEvent;
use App\Components\Balticrest\Service\Auth\UserConfirmServiceInterface;
use App\Components\Balticrest\Service\Auth\UserCreatorInterface;
use App\Components\Balticrest\Service\Form\PasswordForm;
use App\Components\Balticrest\Service\Form\RegistrationForm;
use App\Components\Balticrest\Service\Form\RestorePasswordForm;
use App\Components\Balticrest\Service\Image\UserImageServiceInterface;
use App\Components\Balticrest\Service\Log\HistoryLogger;
use App\Components\Balticrest\Service\Log\HistoryLoggerInterface;
use App\Components\Balticrest\Service\Mail\EmailSenderInterface;
use App\Components\Security\Provider\VkAuthProviderInterface;
use App\Components\Security\Provider\FbAuthProviderInterface;
use App\Entity\User;
use App\Entity\UserConfirmCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use LogicException;

class UserController extends AbstractController
{

    const DEFAULT_USER_ICON = '';

    use TargetPathTrait;

    /**
     * Страница авторизации
     *
     * @Route("/login", name="login")
     *
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @param VkAuthProviderInterface $vkAuthProvider
     * @param FbAuthProviderInterface $fbAuthProvider
     *
     * @return Response
     */
    public function login(
        Request $request,
        AuthenticationUtils $authenticationUtils,
        VkAuthProviderInterface $vkAuthProvider,
        FbAuthProviderInterface $fbAuthProvider
    ): Response {
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
            'vk_url' => $vkAuthProvider->getAuthUrl(),
            'fb_url' => $fbAuthProvider->getAuthUrl()
        ]);
    }

    /**
     * Страница регистрации
     *
     * @Route("/registration", name="registration")
     *
     * @param Request $request
     *
     * @param UserCreatorInterface $userCreator
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     */
    public function registration(
        Request $request,
        UserCreatorInterface $userCreator,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        $form = $this->createForm(RegistrationForm::class, null, ['em' => $this->getDoctrine()->getManager()]);

        $form->handleRequest($request);

        $confirmEmail = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $user = $userCreator->createNotConfirmedUser($formData['email'], $formData['username']);

            if ($user) {
                $eventDispatcher->dispatch(new UserCreatedEvent($user));
            }

            $confirmEmail = $formData['email'];
        }

        return $this->render('balticrest/user/registration.html.twig', [
            'form' => $form->createView(),
            'confirm_email' => $confirmEmail
        ]);
    }

    /**
     * Страница установки нового пароля при регистрации
     *
     * @Route(
     *     "/registration/confirm/{code}",
     *      name="registration_confirm",
     *      requirements={"code"="[0-9a-z]{40}"}
     * )
     *
     * @param string $code
     * @param Request $request
     * @param UserConfirmServiceInterface $userConfirmService
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param HistoryLoggerInterface $historyLogger
     *
     * @return Response
     */
    public function registrationConfirm(
        string $code,
        Request $request,
        UserConfirmServiceInterface $userConfirmService,
        UserPasswordEncoderInterface $userPasswordEncoder,
        HistoryLoggerInterface $historyLogger
    ): Response {
        $template = 'balticrest/user/registration_confirm.html.twig';

        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        $userConfirmCode = $userConfirmService->getUserConfirmCode($code, UserConfirmCode::TYPE_CONFIRM_EMAIL);

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

        if ($user->getIsConfirmed() === true) {
            return $this->render($template, ['user_is_confirmed' => true]);
        }

        $form = $this->createForm(PasswordForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $user->setPassword($userPasswordEncoder->encodePassword($user, $formData['password']));
            $user->setIsConfirmed(true);

            $entityManager->persist($user);
            $entityManager->remove($userConfirmCode);

            $entityManager->flush();

            $historyLogger->log(HistoryLogger::USER_REGISTERED, [], $user);

            return $this->render($template, ['password_successfully_set' => true]);
        }

        return $this->render($template, ['form' => $form->createView()]);
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

            if ($user !== null && $user->getIsActive() && $user->getIsConfirmed()) {
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
     * @param HistoryLoggerInterface $historyLogger
     *
     * @return Response
     */
    public function restoreConfirm(
        string $code,
        Request $request,
        UserConfirmServiceInterface $userConfirmService,
        UserPasswordEncoderInterface $userPasswordEncoder,
        HistoryLoggerInterface $historyLogger
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

            $historyLogger->log(HistoryLogger::USER_RESTORED_PASSWORD, [], $user);

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

    /**
     * API для получения аватара пользователя
     *
     * @Route(
     *     "/api/user/avatar/{id}",
     *      methods={"GET"},
     *      requirements={"page"="\d+"},
     *      name="api.user_avatar"
     * )
     *
     * @param UserImageServiceInterface $userImageService
     * @param int|null $id
     *
     * @return Response
     */
    public function getUserImage(UserImageServiceInterface $userImageService, ?int $id = null): Response
    {
        $avatarContent = $userImageService->getUserAvatarContent($id);

        $response = new Response();
        $response->headers->set('Content-Type', mime_content_type($avatarContent));
        $response->setContent(stream_get_contents($avatarContent));

        return $response;
    }
}
