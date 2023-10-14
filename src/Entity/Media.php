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
    /** @var int|null The unique identifier for the media */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /** @var string|null The type of media (e.g., 'image' or 'video') */
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    /** @var string|null The path or URL of the media file */
    private ?string $path = null;

    #[ORM\Column(length: 255, nullable: true)]
    /** @var string|null The optional description for the media */
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    private ?Trick $trick = null;


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
     * @param string $type 'video' or 'image'
     * @return $this
     */
    public function setType(string $type): static
    {
        $this->type = $type;

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
     * @param string $path the path to this media (e.g., URL or file path).
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
     * @param string|null $description the description of this media
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }
}
