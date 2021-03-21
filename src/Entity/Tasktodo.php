<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TasktodoRepository")
 * @UniqueEntity(
 *      fields={"title"},
 *      message="Ce titre est déjà pris"
 * )
 */
class Tasktodo
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     * @Assert\Length(
     *      min=4, 
     *      max=50, 
     *      minMessage="Titre trop court", 
     *      maxMessage = "Titre trop long"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir du contenu.")
     * @Assert\Length(
     *      min=10, 
     *      minMessage="Contenu trop court"
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $freshDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usertodo", inversedBy="tasktodos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usertodo;

    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->isDone;
    }

    public function toggle($flag)
    {
        $this->isDone = $flag;

        return $this;
    }

    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function getUsertodo(): ?Usertodo
    {
        return $this->usertodo;
    }

    public function setUsertodo(?Usertodo $usertodo): self
    {
        $this->usertodo = $usertodo;

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
