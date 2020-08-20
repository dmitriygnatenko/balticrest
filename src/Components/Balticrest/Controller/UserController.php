<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Event\UserCreatedEvent;
use App\Components\Balticrest\Service\Auth\UserConfirmServiceInterface;
use App\Components\Balticrest\Service\Auth\UserCreatorInterface;
use App\Components\Balticrest\Service\Form\PasswordForm;
use App\Components\Balticrest\Service\Form\RegistrationForm;
use App\Components\Security\Provider\VkAuthProviderInterface;
use App\Components\Security\Provider\FbAuthProviderInterface;
use App\Entity\UserConfirmCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    use TargetPathTrait;

    /**
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
     * @Route(
     *     "/registration/confirm/{code}",
     *      name="confirm",
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
    public function confirm(
        string $code,
        Request $request,
        UserConfirmServiceInterface $userConfirmService,
        UserPasswordEncoderInterface $userPasswordEncoder
    ): Response {
        $template = 'balticrest/user/confirm.html.twig';

        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        $userConfirmCode = $userConfirmService->getUserConfirmCode($code);

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
     * @Route("/restore", name="restore")
     *
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function restore(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // TODO
    }
}
