<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecruterRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecruterRepository")
 * @Vich\Uploadable()
 * @UniqueEntity(fields="email")
 */
class Recruter implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit contenir au minimum huit caractères")
     * @Assert\EqualTo(propertyPath="password_verify", message="Vos mots de passe ne sont pas identiques")
     */
    private $password;

    /**
     *  @Assert\EqualTo(propertyPath="password", message="Vos mots de passe ne sont pas identiques")
     * @var string|null
     */
    public $password_verify;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $company;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="recruterExpediteur")
     */
    private $messagesRecu_recruter;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="recruterDestinataire")
     */
    private $messages_recruter;


    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userDestinataire")
     */
    private $messages_user;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="recruter", cascade = {"persist"})
     */
    private $media;


    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->messages_recruter = new ArrayCollection();
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }


    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setRecruterExpediteur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getRecruterExpediteur() === $this) {
                $message->setRecruterExpediteur(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Message[]
     */


    /**
     * Get the value of messages_recruter
     */
    public function getMessagesRecu_recruter()
    {
        return $this->messagesRecu_recruter;
    }

    /**
     * Set the value of messages_recruter
     *
     * @return  self
     */
    public function setMessagesRecu_recruter($messagesRecu_recruter)
    {
        $this->messagesRecu_recruter = $messagesRecu_recruter;

        return $this;
    }

    /**
     * Get the value of messages_user
     */
    public function getMessages_user()
    {
        return $this->messages_user;
    }

    /**
     * Set the value of messages_user
     *
     * @return  self
     */
    public function setMessages_user($messages_user)
    {
        $this->messages_user = $messages_user;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getSlug();
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setRecruter($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getRecruter() === $this) {
                $medium->setRecruter(null);
            }
        }

        return $this;
    }
}
