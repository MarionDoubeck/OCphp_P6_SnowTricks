<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setTrick($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
            // set the owning side to null (unless already changed)
            if ($medium->getTrick() === $this) {
                $medium->setTrick(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    //not to have error in forms
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

    public function getcategory(): ?Category
    {
        return $this->category;
    }

    public function setcategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeImmutable
    {
        return $this->edited_at;
    }

    public function setEditedAt(?\DateTimeImmutable $edited_at): static
    {
        $this->edited_at = $edited_at;

        return $this;
    }

    public function getFeaturedImg(): ?media
    {
        return $this->featured_img;
    }

    public function setFeaturedImg(?media $featured_img): static
    {
        $this->featured_img = $featured_img;

        return $this;
    }

    public function removeFeaturedImg(): static
    {
        
        $this->setFeaturedImg(null);

        return $this;
    }
}
