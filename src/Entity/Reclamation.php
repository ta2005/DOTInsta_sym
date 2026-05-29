<?php

namespace App\Entity;

use App\Enum\StatutReclamationEnum;
use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Controle $controle_id = null;

    #[ORM\Column(length: 255, enumType: StatutReclamationEnum::class)]
    private ?StatutReclamationEnum $statut = null;

    #[ORM\ManyToOne]
    private ?Admin $admin_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getControleId(): ?Controle
    {
        return $this->controle_id;
    }

    public function setControleId(?Controle $controle_id): static
    {
        $this->controle_id = $controle_id;

        return $this;
    }

    public function getStatut(): ?StatutReclamationEnum
    {
        return $this->statut;
    }

    public function setStatut(StatutReclamationEnum $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAdminId(): ?Admin
    {
        return $this->admin_id;
    }

    public function setAdminId(?Admin $admin_id): static
    {
        $this->admin_id = $admin_id;

        return $this;
    }
}
