<?php
// src/Controller/HomeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        $message = null;

        if ($user) {
            if (method_exists($user, 'isEstValide') && !$user->isEstValide()) {
                if ($this->isGranted('ROLE_ETUDIANT')) {
                    $message = 'Votre compte étudiant est en attente de validation par l\'administrateur. Vous serez notifié par email une fois votre compte approuvé.';
                } elseif ($this->isGranted('ROLE_ENTREPRISE')) {
                    $message = 'Votre compte entreprise est en attente de validation par l\'administrateur. Vous serez notifié par email une fois votre compte approuvé.';
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'message' => $message,
        ]);
    }
}
