<?php
// App\Security\LoginSuccessHandler.php

namespace App\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {

    protected $router;
    protected $authorizationChecker;

    public function __construct(Router $router, AuthorizationChecker $authorizationChecker) {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {

        $response = null;
/*
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('admin_sonata_user_user_list'));
        } else if ($this->authorizationChecker->isGranted('ROLE_COURRIER_GESTION')) {
            $response = new RedirectResponse($this->router->generate('admin_app_courrier_list'));
        } else if ($this->authorizationChecker->isGranted('ROLE_EMPLOYE')) {
            $response = new RedirectResponse($this->router->generate('courrier_mes_arrivees'));
        } else if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            $response = new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
        }
        */
        $response = new RedirectResponse($this->router->generate('sonata_admin_dashboard'));

        return $response;
    }

}