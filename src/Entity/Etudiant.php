<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
#[ORM\Table(name: 'etudiants')]
class Etudiant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_etudiant")]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    #[ORM\Column(name: "date_inscription", type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column(name: "est_valide")]
    private ?bool $estValide = null;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Candidature::class)]
    private Collection $candidatures;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: AvisEntreprise::class)]
    private Collection $avisEntreprises;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        $this->avisEntreprises = new ArrayCollection();
        $this->dateInscription = new \DateTime();
        $this->estValide = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function isEstValide(): ?bool
    {
        return $this->estValide;
    }

    public function setEstValide(bool $estValide): static
    {
        $this->estValide = $estValide;

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setEtudiant($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getEtudiant() === $this) {
                $candidature->setEtudiant(null);
            }
        }

        return $this;
    }


    public function getAvisEntreprises(): Collection
    {
        return $this->avisEntreprises;
    }

    public function addAvisEntreprise(AvisEntreprise $avisEntreprise): static
    {
        if (!$this->avisEntreprises->contains($avisEntreprise)) {
            $this->avisEntreprises->add($avisEntreprise);
            $avisEntreprise->setEtudiant($this);
        }

        return $this;
    }

    public function removeAvisEntreprise(AvisEntreprise $avisEntreprise): static
    {
        if ($this->avisEntreprises->removeElement($avisEntreprise)) {
            // set the owning side to null (unless already changed)
            if ($avisEntreprise->getEtudiant() === $this) {
                $avisEntreprise->setEtudiant(null);
            }
        }

        return $this;
    }


    public function getRoles(): array
    {
        return ['ROLE_ETUDIANT'];
    }

    public function getPassword(): string
    {
        return $this->mdp;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {

    }

    public function __toString(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
