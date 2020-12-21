<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade = {"persist"})
     */
    private $expediteur;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recruter", cascade = {"persist"})
     */
    private $expediteur_recruter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"})
     */
    private $destinataire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recruter", cascade={"persist"})
     */
    private $destinataire_recruter;


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

    public function getExpediteur(): ?User
    {
        return $this->expediteur;
    }

    public function setExpediteur(?User $expediteur): self
    {
        $this->expediteur = $expediteur;

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


    /**
     * Get the value of destinataire
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }

    /**
     * Set the value of userDestinataire
     *
     * @return  self
     */
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    /**
     * Get the value of destinataire
     */
    public function getDestinataireRecruter()
    {
        return $this->destinataire_recruter;
    }

    /**
     * Set the value of userDestinataire
     *
     * @return  self
     */
    public function setDestinataireRecruter($destinataire_recruter)
    {
        $this->destinataire_recruter = $destinataire_recruter;

        return $this;
    }

    /**
     * Get the value of expediteur_recruter
     */
    public function getExpediteur_recruter()
    {
        return $this->expediteur_recruter;
    }

    /**
     * Set the value of expediteur_recruter
     *
     * @return  self
     */
    public function setExpediteur_recruter($expediteur_recruter)
    {
        $this->expediteur_recruter = $expediteur_recruter;

        return $this;
    }
}
