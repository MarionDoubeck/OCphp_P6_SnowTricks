<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trick $trick = null;

    #[ORM\Column(options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;


    /**
     * Get the unique identifier for the comment.
     *
     * @return int|null The comment ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the content of the comment.
     *
     * @return string|null The comment content.
     */
    public function getContent(): ?string
    {
        return $this->content;
    }


    /**
     * Set the content of the comment.
     *
     * @param string $content The comment content.
     * @return $this
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }


    /**
     * Get the user associated with the comment.
     *
     * @return User|null The user who made the comment.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }


    /**
     * Set the user associated with the comment.
     *
     * @param User|null $user The user who made the comment.
     * @return $this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }


    /**
     * Get the trick associated with the comment.
     *
     * @return Trick|null The trick the comment is associated with.
     */
    public function getTrick(): ?Trick
    {
        return $this->trick;
    }


    /**
     * Set the trick associated with the comment.
     *
     * @param Trick|null $trick The trick the comment is associated with.
     * @return $this
     */
    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }


    /**
     * Get the creation date and time of the comment.
     *
     * @return \DateTimeImmutable|null The comment's creation timestamp.
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }


    /**
     * Set the creation date and time of the comment.
     *
     * @param \DateTimeImmutable $created_at The comment's creation timestamp.
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
