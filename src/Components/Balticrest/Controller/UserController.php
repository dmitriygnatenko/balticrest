<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Auth\UserCreatorInterface;
use App\Components\Balticrest\Service\Form\RegistrationForm;
use App\Components\Security\Provider\VkAuthProviderInterface;
use App\Components\Security\Provider\FbAuthProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
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
     * @return Response
     */
    public function registration(Request $request, UserCreatorInterface $userCreator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        $form = $this->createForm(RegistrationForm::class, null, ['em' => $this->getDoctrine()->getManager()]);

        $form->handleRequest($request);

        $confirmEmail = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            //$user = $authManager->createNotConfirmedUser($formData['email'], $formData['username']);

            // TODO отправить письмо на почту с инструкцией по активации - событие

            $confirmEmail = $formData['email'];
        }

        return $this->render('balticrest/user/registration.html.twig', [
            'form' => $form->createView(),
            'confirm_email' => $confirmEmail
        ]);
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

    }
}
