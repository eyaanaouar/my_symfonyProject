<?php
namespace App\Security;

use App\Entity\Etudiant;
use App\Entity\Entreprise;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        // Si c'est un étudiant ou une entreprise, on vérifie le champ estValide
        if ($user instanceof Etudiant || $user instanceof Entreprise) {
            if ($user->getEstValide() === 0) {
                throw new CustomUserMessageAccountStatusException('Votre compte n\'a pas encore été approuvé par l\'administrateur.');
            }
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Rien à faire après l'authentification
    }
}
