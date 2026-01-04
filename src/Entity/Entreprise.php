<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
#[ORM\Table(name: 'entreprises')]
class Entreprise implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_entreprise")]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    #[ORM\Column(name: "date_inscription", type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column(type: 'boolean')]
    private bool $estValide = false;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: OffreStage::class)]
    private Collection $offreStages;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: AvisEntreprise::class)]
    private Collection $avisEntreprises;

    public function __construct()
    {
        $this->offreStages = new ArrayCollection();
        $this->avisEntreprises = new ArrayCollection();
        $this->dateInscription = new \DateTime();
        $this->estValide = false;
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): static { $this->adresse = $adresse; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(?string $telephone): static { $this->telephone = $telephone; return $this; }
    public function getMdp(): ?string { return $this->mdp; }
    public function setMdp(string $mdp): static { $this->mdp = $mdp; return $this; }
    public function getDateInscription(): ?\DateTimeInterface { return $this->dateInscription; }
    public function setDateInscription(\DateTimeInterface $dateInscription): static { $this->dateInscription = $dateInscription; return $this; }

    public function isEstValide(): bool { return $this->estValide; }
    public function setEstValide(bool $estValide): static { $this->estValide = $estValide; return $this; }

    public function getOffreStages(): Collection { return $this->offreStages; }
    public function getAvisEntreprises(): Collection { return $this->avisEntreprises; }

    public function getRoles(): array { return ['ROLE_ENTREPRISE']; }
    public function getPassword(): string { return $this->mdp; }
    public function getSalt(): ?string { return null; }
    public function getUsername(): string { return $this->email; }
    public function getUserIdentifier(): string { return $this->email; }
    public function eraseCredentials(): void { }
    public function __toString(): string { return $this->nom; }
}
