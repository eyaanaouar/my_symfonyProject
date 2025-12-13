<?php
// src/Controller/EtudiantController.php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\OffreStage;
use App\Form\CandidatureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etudiant')]
class EtudiantController extends AbstractController
{
    #[Route('/', name: 'app_etudiant_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');

        $etudiant = $this->getUser();
        $candidatures = $entityManager->getRepository(Candidature::class)
            ->findBy(['etudiant' => $etudiant]);

        return $this->render('etudiant/dashboard.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    #[Route('/offres', name: 'app_etudiant_offres')]
    public function offres(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');

        $offres = $entityManager->getRepository(OffreStage::class)
            ->findBy(['estValide' => true]);

        return $this->render('etudiant/offres.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/candidater/{id}', name: 'app_etudiant_candidater')]
    public function candidater(Request $request, OffreStage $offre, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');

        $candidature = new Candidature();
        $candidature->setEtudiant($this->getUser());
        $candidature->setOffre($offre);

        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidature);
            $entityManager->flush();

            $this->addFlash('success', 'Candidature envoyée avec succès!');
            return $this->redirectToRoute('app_etudiant_dashboard');
        }

        return $this->render('etudiant/candidater.html.twig', [
            'form' => $form->createView(),
            'offre' => $offre,
        ]);
    }

    #[Route('/candidatures', name: 'app_etudiant_candidatures')]
    public function candidatures(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');

        $etudiant = $this->getUser();
        $candidatures = $entityManager->getRepository(Candidature::class)
            ->findBy(['etudiant' => $etudiant], ['dateCandidature' => 'DESC']);

        return $this->render('etudiant/candidatures.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

}
