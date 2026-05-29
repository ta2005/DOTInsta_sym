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

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\Column(length: 255, enumType: TypeControleEnum::class)]
    private ?TypeControleEnum $type = null;

    #[ORM\Column(length: 255, enumType: StatutNoteEnum::class)]
    private ?StatutNoteEnum $statut = null;

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
        return $this->type?->value . ' - ' . ($this->enseignement_id?->getNom() ?? '');
    }
}
