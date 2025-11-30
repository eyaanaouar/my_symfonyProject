<?php
// src/Entity/Document.php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\Table(name: 'documents')]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_document")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(name: "id_candidature", referencedColumnName: "id_candidature", nullable: false)]
    private ?Candidature $candidature = null;

    #[ORM\Column(name: "type_document", length: 50)]
    private ?string $typeDocument = null;

    #[ORM\Column(name: "chemin_fichier", length: 255)]
    private ?string $cheminFichier = null;

    #[ORM\Column(name: "date_upload", type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateUpload = null;

    public function __construct()
    {
        $this->dateUpload = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidature(): ?Candidature
    {
        return $this->candidature;
    }

    public function setCandidature(?Candidature $candidature): static
    {
        $this->candidature = $candidature;

        return $this;
    }

    public function getTypeDocument(): ?string
    {
        return $this->typeDocument;
    }

    public function setTypeDocument(string $typeDocument): static
    {
        $this->typeDocument = $typeDocument;

        return $this;
    }

    public function getCheminFichier(): ?string
    {
        return $this->cheminFichier;
    }

    public function setCheminFichier(string $cheminFichier): static
    {
        $this->cheminFichier = $cheminFichier;

        return $this;
    }

    public function getDateUpload(): ?\DateTimeInterface
    {
        return $this->dateUpload;
    }

    public function setDateUpload(\DateTimeInterface $dateUpload): static
    {
        $this->dateUpload = $dateUpload;

        return $this;
    }

    public function __toString(): string
    {
        return $this->typeDocument . ' - ' . $this->cheminFichier;
    }
}
