<?php

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

        return $this->render('admin/dashboard.html.twig', [
            'totalEtudiants' => count($entityManager->getRepository(Etudiant::class)->findAll()),
            'totalEntreprises' => count($entityManager->getRepository(Entreprise::class)->findAll()),
            'totalOffres' => count($entityManager->getRepository(OffreStage::class)->findAll()),
            'etudiantsEnAttente' => $entityManager->getRepository(Etudiant::class)->findBy(['estValide' => false]),
            'entreprisesEnAttente' => $entityManager->getRepository(Entreprise::class)->findBy(['estValide' => false]),
            'offresEnAttente' => $entityManager->getRepository(OffreStage::class)->findBy(['estValide' => false]),
        ]);
    }

    // VALIDATION ÉTUDIANT
    #[Route('/etudiant/{id}/valider', name: 'app_admin_etudiant_valider')]
    public function validerEtudiant(Etudiant $etudiant, EntityManagerInterface $entityManager): Response
    {
        $etudiant->setEstValide(true);
        $entityManager->flush();
        $this->addFlash('success', 'Étudiant approuvé !');
        return $this->redirectToRoute('app_admin_dashboard');
    }

    // VALIDATION ENTREPRISE
    #[Route('/entreprise/{id}/valider', name: 'app_admin_entreprise_valider')]
    public function validerEntreprise(Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        $entreprise->setEstValide(true);
        $entityManager->flush();
        $this->addFlash('success', 'Entreprise approuvée !');
        return $this->redirectToRoute('app_admin_dashboard');
    }

    // VALIDATION OFFRE
    #[Route('/offre/{id}/valider', name: 'app_admin_offre_valider')]
    public function validerOffre(OffreStage $offre, EntityManagerInterface $entityManager): Response
    {
        $offre->setEstValide(true);
        $entityManager->flush();
        $this->addFlash('success', 'L\'offre de stage a été publiée et est maintenant visible par les étudiants.');
        return $this->redirectToRoute('app_admin_dashboard');
    }

}

