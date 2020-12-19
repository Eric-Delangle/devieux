<?php

namespace App\Entity;


use DateTimeInterface;
use App\Entity\Recruter;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable()
 * @UniqueEntity(fields="email")
 */
class User implements UserInterface
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
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="users")
     */
    private $categories;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $experience;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mainAvatar;

    /**
     * @Vich\UploadableField(mapping="user_images", fileNameProperty="mainAvatar")
     * @Assert\File(
     * maxSize="1000k",
     * maxSizeMessage="Le fichier excède 1000Ko.",
     * mimeTypes={"image/jpeg", "image/jpg", "image/gif"},
     * mimeTypesMessage= "formats autorisés: png, jpeg, jpg, gif"
     * )
     * @var File|null
     */
    private $avatarFile;


    /**
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="recruterExpediteur")
     */
    private $messagesRecu_recruter;


    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userExpediteur")
     */
    private $messagesRecu_user;


    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userDestinataire")
     */
    private $messages_user;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->roles = ['ROLE_USER'];


        $this->messages = new ArrayCollection();
        $this->messagesRecu_user = new ArrayCollection();
        $this->messagesRecu_recruter = new ArrayCollection();
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

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getMainAvatar(): ?string
    {
        return $this->mainAvatar;
    }

    public function setMainAvatar(string $mainAvatar): self
    {
        $this->mainAvatar = $mainAvatar;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAvatarFile(): ?File
    {

        return $this->avatarFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $AvatarFile
     *  @return User
     */
    public function setAvatarFile(?File $avatarFile): User
    {
        $this->avatarFile = $avatarFile;
        if ($this->avatarFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }

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

    public function __toString()
    {
        return (string) $this->getSlug();
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
            $message->setUserExpediteur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUserExpediteur() === $this) {
                $message->setUserExpediteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessagesRecuUser(): Collection
    {
        return $this->messages_user;
    }

    public function addMessagesRecuUser(Message $messagesRecuUser): self
    {
        if (!$this->messagesRecu_user->contains($messagesRecuUser)) {
            $this->messagesRecu_user[] = $messagesRecuUser;
            $messagesRecuUser->setUserDestinataire($this);
        }

        return $this;
    }

    public function removeMessagesRecuUser(Message $messagesRecuUser): self
    {
        if ($this->messagesRecu_user->removeElement($messagesRecuUser)) {
            // set the owning side to null (unless already changed)
            if ($messagesRecuUser->getUserDestinataire() === $this) {
                $messagesRecuUser->setUserDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessagesRecuRecruter(): Collection
    {
        return $this->messages_recruter;
    }

    public function addMessagesRecuRecruter(Message $messagesRecuRecruter): self
    {
        if (!$this->messagesRecu_recruter->contains($messagesRecuRecruter)) {
            $this->messagesRecu_recruter[] = $messagesRecuRecruter;
            $messagesRecuRecruter->setUserDestinataire($this);
        }

        return $this;
    }

    public function removeMessagesRecuRecruter(Message $messagesRecuRecruter): self
    {
        if ($this->messagesRecu_recruter->removeElement($messagesRecuRecruter)) {
            // set the owning side to null (unless already changed)
            if ($messagesRecuRecruter->getUserDestinataire() === $this) {
                $messagesRecuRecruter->setUserDestinataire(null);
            }
        }

        return $this;
    }
}
