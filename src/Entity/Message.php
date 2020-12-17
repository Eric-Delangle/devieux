<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     */
    private $userExpediteur;

    /**
     * @ORM\ManyToOne(targetEntity=Recruter::class, inversedBy="messages")
     */
    private $recruterExpediteur;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     */
    private $destinataire;




    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postedAt;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserExpediteur(): ?User
    {
        return $this->userExpediteur;
    }

    public function setUserExpediteur(?User $userExpediteur): self
    {
        $this->userExpediteur = $userExpediteur;

        return $this;
    }

    public function getRecruterExpediteur(): ?Recruter
    {
        return $this->recruterExpediteur;
    }

    public function setRecruterExpediteur(?Recruter $recruterExpediteur): self
    {
        $this->recruterExpediteur = $recruterExpediteur;

        return $this;
    }


    public function getDestinataire(): ?User
    {
        return $this->destinataire;
    }

    public function setDestinataire(?User $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }


    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPostedAt(): ?\DateTimeInterface
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTimeInterface $postedAt): self
    {
        $this->postedAt = $postedAt;

        return $this;
    }
}
