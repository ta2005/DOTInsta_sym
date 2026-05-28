<?php

namespace App\Entity;

use App\Repository\NiveauScolaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class NiveauScolaire
{
    #[ORM\Column(length: 255,nullable: true)]
    private ?string $classe = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $niveau = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $fillier = null;


    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getFillier(): ?string
    {
        return $this->fillier;
    }

    public function setFillier(string $fillier): static
    {
        $this->fillier = $fillier;

        return $this;
    }
}
