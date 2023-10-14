<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @var int|null The unique identifier of the group */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /** @var string|null The name of the group */
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    /** @var string|null The description of the group */
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Trick::class, orphanRemoval: true)]
    private Collection $tricks;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }


    /**
     * Get the unique identifier for the group.
     *
     * @return int|null The group ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the name of the group.
     *
     * @return string|null The group name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the name of the group.
     *
     * @param string $name The group name.
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Get the description of the group.
     *
     * @return string|null The group description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * Set the description of the group.
     *
     * @param string $description The group description.
     * @return $this
     */
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
     * @return Collection<int, Trick>
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTrick(Trick $trick): static
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks->add($trick);
            $trick->setCategory($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): static
    {
        if ($this->tricks->removeElement($trick)) {
            // set the owning side to null (unless already changed)
            if ($trick->getCategory() === $this) {
                $trick->setCategory(null);
            }
        }

        return $this;
    }

}
