<?php
// src/Controller/LoginController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, vérifier s'il est validé
        if ($this->getUser()) {
            $user = $this->getUser();

            // Vérifier si c'est un étudiant ou entreprise non validé
            if (method_exists($user, 'isEstValide') && !$user->isEstValide()) {
                // Déconnecter l'utilisateur non validé
                $this->addFlash('danger', 'Votre compte est en attente de validation par l\'administrateur.');
                return $this->redirectToRoute('app_logout');
            }

            return $this->redirectBasedOnRole();
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    private function redirectBasedOnRole(): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        if ($this->isGranted('ROLE_ENTREPRISE')) {
            // Vérifier si l'entreprise est validée
            if (method_exists($user, 'isEstValide') && !$user->isEstValide()) {
                $this->addFlash('danger', 'Votre compte entreprise est en attente de validation par l\'administrateur.');
                return $this->redirectToRoute('app_logout');
            }
            return $this->redirectToRoute('app_entreprise_dashboard');
        }

        if ($this->isGranted('ROLE_ETUDIANT')) {
            // Vérifier si l'étudiant est validé
            if (method_exists($user, 'isEstValide') && !$user->isEstValide()) {
                $this->addFlash('danger', 'Votre compte étudiant est en attente de validation par l\'administrateur.');
                return $this->redirectToRoute('app_logout');
            }
            return $this->redirectToRoute('app_etudiant_dashboard');
        }

        return $this->redirectToRoute('app_home');
    }
}
