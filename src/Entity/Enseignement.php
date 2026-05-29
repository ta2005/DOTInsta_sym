<?php

namespace App\Entity;

use App\Repository\EnseignementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignementRepository::class)]
class Enseignement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Embedded(class: NiveauScolaire::class)]
    private NiveauScolaire $niveau_scolaire_info;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Enseignant $professeur_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matiere $matiere_id = null;

    public function __construct()
    {
        $this->niveau_scolaire_info = new NiveauScolaire();
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getNiveauScolaireInfo(): NiveauScolaire
    {
        return $this->niveau_scolaire_info;
    }

    public function setNiveauScolaireInfo(NiveauScolaire $niveau_scolaire_info): static
    {
        $this->niveau_scolaire_info = $niveau_scolaire_info;

        return $this;
    }

    public function getProfesseurId(): ?Enseignant
    {
        return $this->professeur_id;
    }

    public function setProfesseurId(?Enseignant $professeur_id): static
    {
        $this->professeur_id = $professeur_id;

        return $this;
    }

    public function getMatiereId(): ?Matiere
    {
        return $this->matiere_id;
    }

    public function setMatiereId(?Matiere $matiere_id): static
    {
        $this->matiere_id = $matiere_id;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? '';
    }
}
