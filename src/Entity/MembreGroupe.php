<?php

namespace App\Entity;

use App\Repository\MembreGroupeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembreGroupeRepository::class)]
class MembreGroupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_adhesion = null;

    #[ORM\ManyToOne(inversedBy: 'yes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupe $groupe_d = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAdhesion(): ?\DateTime
    {
        return $this->date_adhesion;
    }

    public function setDateAdhesion(\DateTime $date_adhesion): static
    {
        $this->date_adhesion = $date_adhesion;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getGroupeD(): ?Groupe
    {
        return $this->groupe_d;
    }

    public function setGroupeD(?Groupe $groupe_d): static
    {
        $this->groupe_d = $groupe_d;

        return $this;
    }
}
