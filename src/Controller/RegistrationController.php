<?php
// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Entreprise;
use App\Form\RegistrationEtudiantType;
use App\Form\RegistrationEntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function chooseRegistration(): Response
    {
        return $this->render('registration/choose_registration.html.twig');
    }

    #[Route('/register/etudiant', name: 'app_register_etudiant')]
    public function registerEtudiant(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(RegistrationEtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $etudiant->setMdp(
                $userPasswordHasher->hashPassword(
                    $etudiant,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($etudiant);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte étudiant a été créé avec succès!');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register_etudiant.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/entreprise', name: 'app_register_entreprise')]
    public function registerEntreprise(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(RegistrationEntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $entreprise->setMdp(
                $userPasswordHasher->hashPassword(
                    $entreprise,
                    $form->get('plainPassword')->getData()
                )
            );

            // Marquer l'entreprise comme validée (ou pas selon votre logique métier)
            $entreprise->setEstValide(true); // ← AJOUTER CETTE LIGNE

            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte entreprise a été créé avec succès!');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register_entreprise.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
