<?php
// src/Entity/AvisEntreprise.php

namespace App\Entity;

use App\Repository\AvisEntrepriseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisEntrepriseRepository::class)]
#[ORM\Table(name: 'avis_entreprises')]
class AvisEntreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_avis")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'avisEntreprises')]
    #[ORM\JoinColumn(name: "id_etudiant", referencedColumnName: "id_etudiant", nullable: false)]
    private ?Etudiant $etudiant = null;

    #[ORM\ManyToOne(inversedBy: 'avisEntreprises')]
    #[ORM\JoinColumn(name: "id_entreprise", referencedColumnName: "id_entreprise", nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(name: "date_avis", type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAvis = null;

    public function __construct()
    {
        $this->dateAvis = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): static
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateAvis(): ?\DateTimeInterface
    {
        return $this->dateAvis;
    }

    public function setDateAvis(\DateTimeInterface $dateAvis): static
    {
        $this->dateAvis = $dateAvis;

        return $this;
    }

    public function __toString(): string
    {
        return 'Avis de ' . $this->etudiant . ' sur ' . $this->entreprise;
    }
}
