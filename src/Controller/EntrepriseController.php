<?php
// src/Controller/EntrepriseController.php

namespace App\Controller;

use App\Entity\OffreStage;
use App\Form\OffreStageType;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entreprise')]
class EntrepriseController extends AbstractController
{
    #[Route('/', name: 'app_entreprise_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $entreprise = $this->getUser();
        $offres = $entityManager->getRepository(OffreStage::class)
            ->findBy(['entreprise' => $entreprise]);

        return $this->render('entreprise/dashboard.html.twig', [
            'offres' => $offres,
            'entreprise' => $entreprise,
        ]);
    }

    #[Route('/offre/new', name: 'app_entreprise_offre_new')]
    public function newOffre(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $offre = new OffreStage();
        $offre->setEntreprise($this->getUser());

        $form = $this->createForm(OffreStageType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Valider automatiquement l'offre pour le développement
            $offre->setEstValide(true); // ← AJOUTER CETTE LIGNE

            $entityManager->persist($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Offre de stage créée avec succès!');
            return $this->redirectToRoute('app_entreprise_dashboard');
        }

        return $this->render('entreprise/offre_form.html.twig', [
            'form' => $form->createView(),
            'edit' => false,
        ]);
    }
    #[Route('/offre/{id}/edit', name: 'app_entreprise_offre_edit')]
    public function editOffre(Request $request, OffreStage $offre, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        // Vérifier que l'offre appartient à l'entreprise connectée
        if ($offre->getEntreprise() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cette offre.');
        }

        $form = $this->createForm(OffreStageType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Offre de stage modifiée avec succès!');
            return $this->redirectToRoute('app_entreprise_dashboard');
        }

        return $this->render('entreprise/offre_form.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
            'offre' => $offre,
        ]);
    }
    #[Route('/candidatures/{id}', name: 'app_entreprise_candidatures')]
    public function candidatures(OffreStage $offre, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        // Vérifier que l'offre appartient à l'entreprise connectée
        if ($offre->getEntreprise() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $candidatures = $offre->getCandidatures();

        return $this->render('entreprise/candidatures.html.twig', [
            'candidatures' => $candidatures,
            'offre' => $offre,
        ]);
    }
    #[Route('/candidature/{id}/accepter', name: 'app_candidature_accepter', methods: ['GET'])]
    public function accepter(Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $entreprise = $this->getUser();
        $offre = $candidature->getOffre();

        if ($offre->getEntreprise() !== $entreprise) {
            $this->addFlash('error', 'Vous n\'avez pas le droit de modifier cette candidature.');
            return $this->redirectToRoute('app_entreprise_dashboard');
        }

        $candidature->setStatut('accepte');
        $entityManager->flush();

        $this->addFlash('success', 'La candidature a été acceptée.');

        return $this->redirectToRoute('app_entreprise_candidatures', [
            'id' => $offre->getId(),
        ]);
    }
    #[Route('/candidature/{id}/refuser', name: 'app_candidature_refuser', methods: ['GET'])]
    public function refuser(Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $entreprise = $this->getUser();
        $offre = $candidature->getOffre();

        if ($offre->getEntreprise() !== $entreprise) {
            $this->addFlash('error', 'Vous n\'avez pas le droit de modifier cette candidature.');
            return $this->redirectToRoute('app_entreprise_dashboard');
        }

        $candidature->setStatut('refuse');
        $entityManager->flush();

        $this->addFlash('success', 'La candidature a été refusée.');

        return $this->redirectToRoute('app_entreprise_candidatures', [
            'id' => $offre->getId(),
        ]);
    }
}
