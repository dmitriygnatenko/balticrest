<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserController extends AbstractController
{
    use TargetPathTrait;

    /**
     * @Route("/login", name="login")
     *
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //      return $this->redirectToRoute('/');
        // }

        if ($request->isMethod('GET')) {
            $referer = $request->headers->get('referer');
            if ($referer !== null && $referer !== $request->getUri()) {
                $this->saveTargetPath($request->getSession(), 'main', $referer);
            }
        }

        return $this->render('balticrest/user/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/register", name="register")
     *
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function register(Request $request, AuthenticationUtils $authenticationUtils): Response
    {

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

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
