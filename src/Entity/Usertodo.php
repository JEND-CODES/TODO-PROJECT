<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsertodoRepository")
 * @UniqueEntity(
 *  fields= {"email"},
 *  message= "Cet email est déjà utilisé"
 * )
 * @UniqueEntity(
 *  fields={"username"},
 *  message="Ce nom est déjà pris"
 * )
 */
class Usertodo implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(
     *      min=8,
     *      minMessage="Votre mot de passe doit comporter au moins 8 caractères"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $freshDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tasktodo", mappedBy="usertodo", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $tasktodos;

    /**
	 * @ORM\Column(type="string", length=80)
	 */
	private $role;

    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->tasktodos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?string
	{
		return $this->role;
	}

	public function setRole(string $role): self
    {
        $this->role = $role;
    
        return $this;
    }

    public function getRoles()
	{
        return [$this->getRole()];
	}

    public function eraseCredentials()
    {
        return null;
    }

    /**
     * @return Collection|Tasktodo[]
     */
    public function getTasktodos(): Collection
    {
        return $this->tasktodos;
    }

    public function addTasktodo(Tasktodo $tasktodo): self
    {
        if (!$this->tasktodos->contains($tasktodo)) {
            $this->tasktodos[] = $tasktodo;
            $tasktodo->setUsertodo($this);
        }

        return $this;
    }

    public function removeTasktodo(Tasktodo $tasktodo): self
    {
        if ($this->tasktodos->removeElement($tasktodo)) {
            // set the owning side to null (unless already changed)
            if ($tasktodo->getUsertodo() === $this) {
                $tasktodo->setUsertodo(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFreshDate(): ?\DateTimeInterface
    {
        return $this->freshDate;
    }

    public function setFreshDate(?\DateTimeInterface $freshDate): self
    {
        $this->freshDate = $freshDate;

        return $this;
    }
}