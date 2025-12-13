<?php


namespace App\Entity;

use App\Repository\OffreStageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreStageRepository::class)]
#[ORM\Table(name: 'offres_stage')]
class OffreStage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_offre")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'offreStages')]
    #[ORM\JoinColumn(name: "id_entreprise", referencedColumnName: "id_entreprise", nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: "date_debut", type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: "date_fin", type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(name: "date_publication", type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\Column(name: "lettre_motivation_requise")]
    private ?bool $lettreMotivationRequise = null;

    #[ORM\Column(name: "est_valide")]
    private ?bool $estValide = null;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Candidature::class)]
    private Collection $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        $this->datePublication = new \DateTime();
        $this->lettreMotivationRequise = false;
        $this->estValide = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function isLettreMotivationRequise(): ?bool
    {
        return $this->lettreMotivationRequise;
    }

    public function setLettreMotivationRequise(bool $lettreMotivationRequise): static
    {
        $this->lettreMotivationRequise = $lettreMotivationRequise;

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


    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setOffre($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getOffre() === $this) {
                $candidature->setOffre(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->titre;
    }
}
