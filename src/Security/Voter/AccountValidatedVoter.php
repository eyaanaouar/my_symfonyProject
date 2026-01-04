<?php

// src/Security/Voter/AccountValidatedVoter.php

namespace App\Security\Voter;

use App\Entity\Etudiant;
use App\Entity\Entreprise;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class AccountValidatedVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // Ce voter s'applique à toutes les routes protégées
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Si pas d'utilisateur, on laisse les autres voters décider
        if (!$user) {
            return true;
        }

        // Pour les admins, toujours autoriser
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Pour les étudiants et entreprises, vérifier estValide
        if ($user instanceof Etudiant || $user instanceof Entreprise) {
            return $user->isEstValide();
        }

        return true;
    }
}
