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
    /** @var int|null The unique identifier */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /** @var string|null The name of the trick */
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    /** @var string|null The description of the trick */
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'parent')]
    /** @var Group|null The group to which the trick belongs */
    private ?Group $trick_group = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Media::class)]
    /** @var Collection<int, Media> The media associated with the trick */
    private Collection $media;

    #[ORM\OneToMany(mappedBy: 'parent2', targetEntity: Comment::class, orphanRemoval: true)]
    /** @var Collection<int, Comment> The comments associated with the trick (with orphan removal) */

    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'parent')]
    #[ORM\JoinColumn(nullable: false)]
    /** @var User|null The user who created the trick (not nullable) */
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;


    /**
     * Construct a new Trick instance.
     */
    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->comments = new ArrayCollection();

    }//end_construct()


    /**
     * Get the unique identifier of this trick.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the name of this trick.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the name of this trick.
     *
     * @param string $name the name of this trick
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Get the description of this trick.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * Set the description of this trick.
     *
     * @param string $description the description of this trick
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }


    /**
     * Get the group to which this trick belongs.
     *
     * @return Group|null
     */
    public function getTrickGroup(): ?Group
    {
        return $this->trick_group;
    }


    /**
     * Set the group to which this trick belongs.
     *
     * @param Group|null $trick_group he group to which this trick belongs
     * @return $this
     */
    public function setTrickGroup(?Group $trick_group): static
    {
        $this->trick_group = $trick_group;
        return $this;
    }


    /**
     * Get the media associated with this trick.
     *
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }


    /**
     * Add a media element to this trick.
     *
     * @param Media $medium the media element to add to the trick
     * @return $this
     */
    public function addMedium(Media $medium): static
    {
        if ($this->media->contains($medium) === FALSE) {
            $this->media->add($medium);
            $medium->setParent($this);
        }

        return $this;
    }


    /**
     * Remove a media element from this trick.
     *
     * @param Media $medium the media element to remove from the trick
     * @return $this
     */
    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium) === TRUE) {
            // Set the owning side to null (unless already changed)
            if ($medium->getParent() === $this) {
                $medium->setParent(null);
            }
        }

        return $this;
    }


    /**
     * Get the comments associated with this trick.
     *
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }


    /**
     * Add a comment element to this trick.
     *
     * @param Comment $comment the comment element to add
     * @return $this
     */
    public function addComment(Comment $comment): static
    {
        if ($this->comments->contains($comment) === FALSE) {
            $this->comments->add($comment);
            $comment->setParent2($this);
        }

        return $this;
    }


    /**
     * Remove a comment element from this trick.
     *
     * @param Comment $comment the comment element to remove
     * @return $this
     */
    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment) === TRUE) {
            // set the owning side to null (unless already changed)
            if ($comment->getParent2() === $this) {
                $comment->setParent2(null);
            }
        }

        return $this;
    }


    /**
     * Get the user who created this trick.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }


    /**
     * Set the user who created this trick.
     *
     * @param User|null $user the user who created this trick
     * @return $this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;
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

}
