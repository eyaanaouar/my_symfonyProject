<?php
// src/Controller/EtudiantController.php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Feedback;
use App\Form\FeedbackType;
use App\Entity\Document;
use App\Entity\OffreStage;
use App\Form\CandidatureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/etudiant')]
class EtudiantController extends AbstractController
{
    // Cette fonction n'est plus appelée, mais nous la laissons
    // au cas où vous l'utiliseriez ailleurs.
    private function checkValidation(): ?Response
    {
        $user = $this->getUser();
        // Correction pour utiliser getEstValide()
        if ($user && method_exists($user, 'getEstValide') && !$user->getEstValide()) {
            $this->addFlash('danger', 'Votre compte est en attente d\'approbation par l\'administrateur.');
            return $this->redirectToRoute('app_home'); // Redirige vers l'accueil
        }
        return null;
    }

    // La méthode dashboard est SIMPLIFIÉE.
    // Plus besoin de TokenStorage ou de Request ici.
    #[Route('/', name: 'app_etudiant_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        // Cette ligne suffit. Si l'utilisateur n'a pas le bon rôle, il est bloqué.
        // Le LoginListener a déjà vérifié si le compte était valide.
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
            ->findBy(['estValide' => true]); // On ne montre que les offres validées

        return $this->render('etudiant/offres.html.twig', [
            'offres' => $offres,
        ]);
    }

    // ... Le reste du contrôleur reste identique ...
    #[Route('/candidater/{id}', name: 'app_etudiant_candidater')]
    public function candidater(Request $request, OffreStage $offre, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');

        $candidature = new Candidature();
        $candidature->setEtudiant($this->getUser());
        $candidature->setOffre($offre);

        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload du CV
            $cvFile = $form->get('cv')->getData();
            if ($cvFile) {
                $this->uploadDocument($cvFile, 'CV', $candidature, $slugger, $entityManager);
            }

            // Gestion de l'upload de la lettre de motivation
            $lmFile = $form->get('lettre_motivation')->getData();
            if ($lmFile) {
                $this->uploadDocument($lmFile, 'Lettre de Motivation', $candidature, $slugger, $entityManager);
            }

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

    private function uploadDocument($file, string $type, Candidature $candidature, SluggerInterface $slugger, EntityManagerInterface $em): void
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move(
            $this->getParameter('documents_directory'),
            $newFilename
        );

        $document = new Document();
        $document->setTypeDocument($type);
        $document->setCheminFichier($newFilename);
        $document->setCandidature($candidature);
        $document->setDateUpload(new \DateTime());

        $em->persist($document);
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

    #[Route('/feedback/{id}', name: 'app_etudiant_feedback')]
    public function addFeedback(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');

        if ($candidature->getEtudiant() !== $this->getUser() || $candidature->getStatut() !== 'accepte') {
            throw $this->createAccessDeniedException("Vous ne pouvez pas laisser de feedback pour ce stage.");
        }

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
