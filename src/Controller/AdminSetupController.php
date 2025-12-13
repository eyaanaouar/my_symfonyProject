<?php
// src/Controller/AdminSetupController.php

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
        // Vérifier si un admin existe déjà
        $existingAdmin = $entityManager->getRepository(Admin::class)->findOneBy(['email' => 'admin@example.com']);

        if ($existingAdmin) {
            return new Response('Admin existe déjà: admin@example.com / admin123');
        }

        // Créer un admin
        $admin = new Admin();
        $admin->setNom('Admin');
        $admin->setPrenom('System');
        $admin->setEmail('admin@example.com');
        $admin->setMdp($passwordHasher->hashPassword($admin, 'admin123'));

        $entityManager->persist($admin);
        $entityManager->flush();

        return new Response('Admin créé:<br><br>Email: admin@example.com<br>Mot de passe: admin123<br><br><a href="/login">Se connecter</a>');
    }
}
