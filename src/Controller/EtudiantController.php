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
    // Dans src/Controller/EtudiantController.php

    #[Route('/feedback/{id}', name: 'app_etudiant_feedback')]
    public function addFeedback(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');

        // Vérifier que la candidature appartient à l'étudiant et qu'elle est acceptée
        if ($candidature->getEtudiant() !== $this->getUser() || $candidature->getStatut() !== 'accepte') {
            throw $this->createAccessDeniedException("Vous ne pouvez pas laisser de feedback pour ce stage.");
        }

        // Vérifier si un feedback existe déjà
        if ($candidature->getFeedback()) {
            $this->addFlash('warning', 'Vous avez déjà donné votre avis pour ce stage.');
            return $this->redirectToRoute('app_etudiant_dashboard');
        }

        $feedback = new Feedback();
        $feedback->setCandidature($candidature);

        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($feedback);
            $entityManager->flush();

            $this->addFlash('success', 'Merci pour votre feedback !');
            return $this->redirectToRoute('app_etudiant_dashboard');
        }

        return $this->render('etudiant/feedback.html.twig', [
            'form' => $form->createView(),
            'candidature' => $candidature
        ]);
    }

}
