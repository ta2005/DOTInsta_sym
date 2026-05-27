<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin extends User
{

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'admin_id', orphanRemoval: true)]
    private Collection $demandeTraite;

    public function __construct()
    {
        $this->demandeTraite = new ArrayCollection();
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

    /**
     * @return Collection<int, Demande>
     */
    public function getdemandeTraite(): Collection
    {
        return $this->demandeTraite;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demandeTraite->contains($demande)) {
            $this->demandeTraite->add($demande);
            $demande->setAdminId($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandeTraite->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getAdminId() === $this) {
                $demande->setAdminId(null);
            }
        }

        return $this;
    }
}
