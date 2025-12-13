<?php
// src/Controller/AdminController.php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Entreprise;
use App\Entity\OffreStage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Statistiques
        $totalEtudiants = count($entityManager->getRepository(Etudiant::class)->findAll());
        $totalEntreprises = count($entityManager->getRepository(Entreprise::class)->findAll());
        $totalOffres = count($entityManager->getRepository(OffreStage::class)->findAll());

        $etudiantsValides = count($entityManager->getRepository(Etudiant::class)->findBy(['estValide' => true]));
        $entreprisesValides = count($entityManager->getRepository(Entreprise::class)->findBy(['estValide' => true]));
        $offresValides = count($entityManager->getRepository(OffreStage::class)->findBy(['estValide' => true]));

        return $this->render('admin/dashboard.html.twig', [
            'totalEtudiants' => $totalEtudiants,
            'totalEntreprises' => $totalEntreprises,
            'totalOffres' => $totalOffres,
            'etudiantsValides' => $etudiantsValides,
            'entreprisesValides' => $entreprisesValides,
            'offresValides' => $offresValides,
        ]);
    }

    #[Route('/etudiants', name: 'app_admin_etudiants')]
    public function etudiants(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $etudiants = $entityManager->getRepository(Etudiant::class)->findAll();

        return $this->render('admin/etudiants.html.twig', [
            'etudiants' => $etudiants,
        ]);
    }

    #[Route('/etudiant/{id}/valider', name: 'app_admin_etudiant_valider')]
    public function validerEtudiant(Etudiant $etudiant, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $etudiant->setEstValide(true);
        $entityManager->flush();

        $this->addFlash('success', 'Étudiant validé avec succès!');
        return $this->redirectToRoute('app_admin_etudiants');
    }

    #[Route('/entreprises', name: 'app_admin_entreprises')]
    public function entreprises(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entreprises = $entityManager->getRepository(Entreprise::class)->findAll();

        return $this->render('admin/entreprises.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    #[Route('/entreprise/{id}/valider', name: 'app_admin_entreprise_valider')]
    public function validerEntreprise(Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entreprise->setEstValide(true);
        $entityManager->flush();

        $this->addFlash('success', 'Entreprise validée avec succès!');
        return $this->redirectToRoute('app_admin_entreprises');
    }

    #[Route('/offres', name: 'app_admin_offres')]
    public function offres(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $offres = $entityManager->getRepository(OffreStage::class)->findAll();

        return $this->render('admin/offres.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/offre/{id}/valider', name: 'app_admin_offre_valider')]
    public function validerOffre(OffreStage $offre, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $offre->setEstValide(true);
        $entityManager->flush();

        $this->addFlash('success', 'Offre de stage validée avec succès!');
        return $this->redirectToRoute('app_admin_offres');
    }

    #[Route('/offre/{id}/refuser', name: 'app_admin_offre_refuser')]
    public function refuserOffre(OffreStage $offre, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $offre->setEstValide(false);
        $entityManager->flush();

        $this->addFlash('warning', 'Offre de stage refusée!');
        return $this->redirectToRoute('app_admin_offres');
    }
}
