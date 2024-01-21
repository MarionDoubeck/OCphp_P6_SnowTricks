<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a trick in the application.
 */
#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Media::class)]
    private Collection $media;

  

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comment;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    private ?Category $category = null;

    #[ORM\Column(type: "datetime_immutable", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeImmutable $created_at;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $edited_at = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?media $featured_img = null;


    /**
     * Trick constructor.
     */
    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }


    /**
     * Get the ID of the trick.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the name of the trick.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the name of the trick.
     *
     * @param string $name the name of the trick
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the description of the trick.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * Set the description of the trick.
     *
     * @param string $description the descritpion of the trick
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }


    /**
     * Get the slug of the trick.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }


    /**
     * Set the slug of the trick.
     *
     * @param string $slug the slug of the trick
     * @return $this
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the collection of media associated with this trick.
     *
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }


    /**
     * Add a media to the trick.
     *
     * @param Media $medium the medium to add to the trick
     * @return $this
     */
    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setTrick($this);
        }

        return $this;
    }


    /**
     * Remove a media from the trick.
     *
     * @param Media $medium the medium to remove from the trick
     * @return $this
     */
    public function removeMedium(Media $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
            // Set the owning side to null (unless already changed).
            if ($medium->getTrick() === $this) {
                $medium->setTrick(null);
            }
        }

        return $this;
    }


    /**
     * Get the user who created the trick.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }


    /**
     * Set the user who created the trick.
     *
     * @param User|null $user the user who created the trick
     * @return $this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the collection of comments associated with this trick.
     *
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }


    /**
     * Add a comment to the trick.
     *
     * @param Comment $comment the comment to add
     * @return $this
     */
    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }


    /**
     * Remove a comment from the trick.
     *
     * @param Comment $comment the comment to remove
     * @return $this
     */
    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // Set the owning side to null (unless already changed).
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * Not to have error in forms.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

     /**
     * Get sorted comments (from newest to oldest).
     *
     * @return Collection|Comment[]
     */
    public function getSortedComments(): Collection
    {
        $sortedComments = $this->comment;
        if ($sortedComments !== null) {
            $sortedComments = $sortedComments->toArray();
            usort($sortedComments, function (Comment $a, Comment $b) {
                return $b->getCreatedAt() <=> $a->getCreatedAt();
            });
        }

        return new ArrayCollection($sortedComments);
    }


    /**
     * Get the category of the trick.
     *
     * @return Category|null
     */
    public function getcategory(): ?Category
    {
        return $this->category;
    }


    /**
     * Set the category of the trick.
     *
     * @param Category|null $category the category of the trick
     * @return $this
     */
    public function setcategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }


    /**
     * Get the creation date of the trick.
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }


    /**
     * Set the creation date of the trick.
     *
     * @param \DateTimeImmutable $created_at the creation date of the trick
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }


    /**
     * Get the last edit date of the trick.
     *
     * @return \DateTimeImmutable|null
     */
    public function getEditedAt(): ?\DateTimeImmutable
    {
        return $this->edited_at;
    }


    /**
     * Set the last edit date of the trick.
     *
     * @param \DateTimeImmutable|null $edited_at the last edit date of the trick
     * @return $this
     */
    public function setEditedAt(?\DateTimeImmutable $edited_at): static
    {
        $this->edited_at = $edited_at;

        return $this;
    }


    /**
     * Get the featured image of the trick.
     *
     * @return media|null
     */
    public function getFeaturedImg(): ?media
    {
        return $this->featured_img;
    }


    /**
     * Set the featured image of the trick.
     *
     * @param media|null $featured_img the featured image of the trick
     * @return $this
     */
    public function setFeaturedImg(?media $featured_img): static
    {
        $this->featured_img = $featured_img;

        return $this;
    }


    /**
     * Remove the featured image from the trick.
     *
     * @return $this
     */
    public function removeFeaturedImg(): static
    {
        
        $this->setFeaturedImg(null);

        return $this;
    }

    
}
