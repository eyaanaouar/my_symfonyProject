<?php
// src/Security/AccessDeniedHandler.php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $urlGenerator;
    private $flashBag;

    public function __construct(UrlGeneratorInterface $urlGenerator, FlashBagInterface $flashBag)
    {
        $this->urlGenerator = $urlGenerator;
        $this->flashBag = $flashBag;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): RedirectResponse
    {
        // Vérifier si l'utilisateur est connecté mais non validé
        $user = $accessDeniedException->getToken()->getUser();

        if ($user && method_exists($user, 'isEstValide') && !$user->isEstValide()) {
            $this->flashBag->add('danger', 'Votre compte est en attente de validation par l\'administrateur.');
            return new RedirectResponse($this->urlGenerator->generate('app_logout'));
        }

        // Redirection par défaut
        $this->flashBag->add('danger', 'Accès refusé.');
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
}
