<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name:'app_user')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap(['user' => User::class, 'admin' => Admin::class,'enseignant'=>Enseignant::class, 'etudiant'=>Etudiant::class])]
class User implements UserInterface,PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $mot_de_pass = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $demandes;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'auteur_id', orphanRemoval: true)]
    private Collection $postsCree;

    /**
     * @var Collection<int, MembreGroupe>
     */
    #[ORM\OneToMany(targetEntity: MembreGroupe::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $yes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
        $this->postsCree = new ArrayCollection();
        $this->yes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;

        return $this;
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

    public function getMotDePass(): ?string
    {
        return $this->mot_de_pass;
    }

    public function setMotDePass(string $mot_de_pass): static
    {
        $this->mot_de_pass = $mot_de_pass;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setUserId($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getUserId() === $this) {
                $demande->setUserId(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier():string{
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER']; // Everyone gets this

        if ($this instanceof Admin) {
            $roles[] = 'ROLE_ADMIN';
        } elseif ($this instanceof Enseignant) {
            $roles[] = 'ROLE_ENSEIGNANT';
        } elseif ($this instanceof Etudiant) {
            $roles[] = 'ROLE_ETUDIANT';
        }

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPassword():?string{
        return $this->mot_de_pass;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPostsCree(): Collection
    {
        return $this->postsCree;
    }

    public function addPostsCree(Post $postsCree): static
    {
        if (!$this->postsCree->contains($postsCree)) {
            $this->postsCree->add($postsCree);
            $postsCree->setAuteurId($this);
        }

        return $this;
    }

    public function removePostsCree(Post $postsCree): static
    {
        if ($this->postsCree->removeElement($postsCree)) {
            // set the owning side to null (unless already changed)
            if ($postsCree->getAuteurId() === $this) {
                $postsCree->setAuteurId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MembreGroupe>
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(MembreGroupe $ye): static
    {
        if (!$this->yes->contains($ye)) {
            $this->yes->add($ye);
            $ye->setUserId($this);
        }

        return $this;
    }

    public function removeYe(MembreGroupe $ye): static
    {
        if ($this->yes->removeElement($ye)) {
            // set the owning side to null (unless already changed)
            if ($ye->getUserId() === $this) {
                $ye->setUserId(null);
            }
        }

        return $this;
    }

}
