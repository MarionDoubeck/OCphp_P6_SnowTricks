<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    private ?Trick $trick = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;


    /**
     * Get the unique identifier of this media.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the type of this media (e.g., image or video).
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }


    /**
     * Set the type of this media (e.g., image or video).
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }


    /**
     * Get the trick associated with this media.
     *
     * @return Trick|null
     */
    public function getTrick(): ?Trick
    {
        return $this->trick;
    }


    /**
     * Set the trick associated with this media.
     *
     * @param Trick|null $trick
     * @return $this
     */
    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }


    /**
     * Get the path to this media (e.g., URL or file path).
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }


    /**
     * Set the path to this media (e.g., URL or file path).
     *
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }


    /**
     * Get the description of this media.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * Set the description of this media.
     *
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
