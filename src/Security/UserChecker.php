<?php
// src/Security/UserChecker.php

namespace App\Security;

use App\Entity\Etudiant;
use App\Entity\Entreprise;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        // DEBUG: Vérifier quelle classe d'utilisateur
        // error_log('UserChecker: Vérification de ' . get_class($user));

        // Vérifier si c'est un étudiant ou entreprise
        if ($user instanceof Etudiant || $user instanceof Entreprise) {
            // DEBUG: Vérifier la valeur de estValide
            // error_log('UserChecker: estValide = ' . ($user->isEstValide() ? 'true' : 'false'));

            // Vérifier si le compte est validé (estValide = true/1)
            if (!$user->isEstValide()) {
                // DEBUG
                // error_log('UserChecker: Compte NON validé - blocage');

                // Le compte n'est pas encore validé par l'administrateur
                throw new CustomUserMessageAuthenticationException(
                    'Votre compte est en attente de validation par l\'administrateur.'
                );
            }

            // DEBUG
            // error_log('UserChecker: Compte validé - autorisé');
        }
        // Pour les admins, on ne vérifie pas estValide
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
