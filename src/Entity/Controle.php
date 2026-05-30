
<?php

namespace App\Entity;

use App\Enum\StatutNoteEnum;
use App\Enum\TypeControleEnum;
use App\Repository\ControleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ControleRepository::class)]
class Controle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\Column(length: 255, enumType: TypeControleEnum::class)]
    private ?TypeControleEnum $type = null;

    #[ORM\Column(length: 255, enumType: StatutNoteEnum::class)]
    private ?StatutNoteEnum $statut = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateControle = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fichier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $corrige = null;

    #[ORM\Column(nullable: true)]
    private ?bool $publie = false;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $salle = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $semestre = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Enseignement $enseignement_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $etudiant_id = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): static
    {
        $this->note = $note;
        return $this;
    }

    public function getType(): ?TypeControleEnum
    {
        return $this->type;
    }

    public function setType(TypeControleEnum $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getStatut(): ?StatutNoteEnum
    {
        return $this->statut;
    }

    public function setStatut(StatutNoteEnum $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateControle(): ?\DateTimeInterface
    {
        return $this->dateControle;
    }

    public function setDateControle(\DateTimeInterface $dateControle): static
    {
        $this->dateControle = $dateControle;
        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): static
    {
        $this->duree = $duree;
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

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): static
    {
        $this->fichier = $fichier;
        return $this;
    }

    public function getCorrige(): ?string
    {
        return $this->corrige;
    }

    public function setCorrige(?string $corrige): static
    {
        $this->corrige = $corrige;
        return $this;
    }

    public function isPublie(): ?bool
    {
        return $this->publie;
    }

    public function setPublie(?bool $publie): static
    {
        $this->publie = $publie;
        return $this;
    }

    public function getSalle(): ?string
    {
        return $this->salle;
    }

    public function setSalle(?string $salle): static
    {
        $this->salle = $salle;
        return $this;
    }

    public function getSemestre(): ?string
    {
        return $this->semestre;
    }

    public function setSemestre(?string $semestre): static
    {
        $this->semestre = $semestre;
        return $this;
    }

    public function getEnseignementId(): ?Enseignement
    {
        return $this->enseignement_id;
    }

    public function setEnseignementId(?Enseignement $enseignement_id): static
    {
        $this->enseignement_id = $enseignement_id;
        return $this;
    }

    public function getEtudiantId(): ?Etudiant
    {
        return $this->etudiant_id;
    }

    public function setEtudiantId(?Etudiant $etudiant_id): static
    {
        $this->etudiant_id = $etudiant_id;
        return $this;
    }

    public function __toString(): string
    {
        return $this->titre;
    }
}
