<?php

namespace App\Entity;

use App\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 * @Vich\Uploadable
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var String|null
     */
    private $caption;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="imageName")
     * @Assert\File(
     * maxSize="1000k",
     * maxSizeMessage="Le fichier excède 1000Ko.",
     * mimeTypes={"image/jpeg", "image/jpg", "image/gif"},
     * mimeTypesMessage= "formats autorisés: png, jpeg, jpg, gif"
     * )
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="media", cascade = {"persist"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Recruter::class, inversedBy="media", cascade = {"persist"})
     */
    private $recruter;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;
        dump($this->imageName);
        return $this;
    }


    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getImageFile(): ?File
    {

        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $ImageFile
     *  @return User
     */
    public function setImageFile(?File $imageFile)
    {

        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        dump($imageFile);
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getImageName();
    }

    /**
     * Get the value of recruter
     */
    public function getRecruter()
    {
        return $this->recruter;
    }

    /**
     * Set the value of recruter
     *
     * @return  self
     */
    public function setRecruter($recruter)
    {
        $this->recruter = $recruter;

        return $this;
    }
}
