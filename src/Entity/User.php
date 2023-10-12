<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Trick::class)]
    private Collection $parent;


    /**
     * Construct a new User instance.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->parent = new ArrayCollection();
    }


    /**
     * Get the unique identifier of this user.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the email of this user.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * Set the email of this user.
     *
     * @param string $email the email of this user
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }


    /**
     * Get the user identifier, which represents this user.
     *
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


    /**
     * Get the roles assigned to this user.
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }


    /**
     * Set the roles assigned to this user.
     *
     * @param array $roles the roles assigned to this user
     * @return $this
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }


    /**
     * Get the hashed password of this user.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * Set the hashed password of this user.
     *
     * @param string $password the hashed password of this user
     * @return $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }


    /**
     * Erase any temporary, sensitive data stored on the user.
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    /**
     * Get the username of this user.
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }


    /**
     * Set the username of this user.
     *
     * @param string $username the username of this user
     * @return $this
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }


    /**
     * Get the avatar path of this user.
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }


    /**
     * Set the avatar path of this user.
     *
     * @param string|null $avatar the avatar path of this user
     * @return $this
     */
    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;
        return $this;
    }


    /**
     * Get the comments associated with this user.
     *
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }


    /**
     * Add a comment associated with this user.
     *
     * @param Comment $comment the comment posted by this user
     * @return $this
     */
    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setParent($this);
        }

        return $this;
    }


    /**
     * Remove a comment associated with this user.
     *
     * @param Comment $comment the comment to remove
     * @return $this
     */
    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getParent() === $this) {
                $comment->setParent(null);
            }
        }

        return $this;
    }


    /**
     * Get the tricks associated with this user.
     *
     * @return Collection<int, Trick>
     */
    public function getParent(): Collection
    {
        return $this->parent;
    }


    /**
     * Add a trick associated with this user.
     *
     * @param Trick $parent the trick posted by this user
     * @return $this
     */
    public function addParent(Trick $parent): static
    {
        if (!$this->parent->contains($parent)) {
            $this->parent->add($parent);
            $parent->setUser($this);
        }

        return $this;
    }


    /**
     * Remove a trick associated with this user.
     *
     * @param Trick $parent the trick by this user to remove
     * @return $this
     */
    public function removeParent(Trick $parent): static
    {
        if ($this->parent->removeElement($parent)) {
            // set the owning side to null (unless already changed)
            if ($parent->getUser() === $this) {
                $parent->setUser(null);
            }
        }
        
        return $this;
    }

}
