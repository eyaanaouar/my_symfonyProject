<?php

namespace App\Controller;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminSetupController extends AbstractController
{
    #[Route('/setup-admin', name: 'app_setup_admin')]
    public function setupAdmin(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // 1. Vérifier si un admin existe déjà pour éviter les doublons
        $existingAdmin = $entityManager->getRepository(Admin::class)->findOneBy(['email' => 'admin@admin.com']);

        if ($existingAdmin) {
            return new Response('L\'administrateur existe déjà. <a href="/login">Se connecter</a>');
        }

        // 2. Créer l'objet Admin
        $admin = new Admin();
        $admin->setEmail('admin@admin.com');
        $admin->setNom('ADMIN');
        $admin->setPrenom('Principal');

        // 3. Hasher le mot de passe
        $hashedPassword = $passwordHasher->hashPassword($admin, 'admin123');
        $admin->setMdp($hashedPassword);

        // 4. Enregistrer en base de données
        $entityManager->persist($admin);
        $entityManager->flush();

        return new Response('Compte Administrateur créé avec succès !
Email : admin@admin.com
Password : admin123

<a href="/login">Se connecter</a>');
    }
}
