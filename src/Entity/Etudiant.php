<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant extends User
{
    #[ORM\Embedded(class: NiveauScolaire::class)]
    private NiveauScolaire $niveauScolaire;

    public function __construct()
    {
        $this->niveauScolaire = new NiveauScolaire();
    }

    public function getNiveauScolaire(): NiveauScolaire
    {
        return $this->niveauScolaire;
    }

    public function setNiveauScolaire(NiveauScolaire $niveauScolaire): static
    {
        $this->niveauScolaire = $niveauScolaire;
        return $this;
    }
}
