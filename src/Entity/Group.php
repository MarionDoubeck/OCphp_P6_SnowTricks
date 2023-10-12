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

    #[ORM\OneToMany(mappedBy: 'trick_group', targetEntity: Trick::class)]
    /** @var Collection The collection of tricks associated with this group */
    private Collection $parent;


    /**
     * Construct a new Group instance.
     */
    public function __construct()
    {
        $this->parent = new ArrayCollection();
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


    /**
     * Get the collection of tricks associated with the group.
     *
     * @return Collection<int, Trick> The collection of tricks.
     */
    public function getParent(): Collection
    {
        return $this->parent;
    }


    /**
     * Add a trick to the collection associated with the group.
     *
     * @param Trick $parent The trick to be added.
     * @return $this
     */
    public function addParent(Trick $parent): static
    {
        if (!$this->parent->contains($parent)) {
            $this->parent->add($parent);
            $parent->setTrickGroup($this);
        }

        return $this;
    }


    /**
     * Remove a trick from the collection associated with the group.
     *
     * @param Trick $parent The trick to be removed.
     * @return $this
     */
    public function removeParent(Trick $parent): static
    {
        if ($this->parent->removeElement($parent)) {
            // set the owning side to null (unless already changed)
            if ($parent->getTrickGroup() === $this) {
                $parent->setTrickGroup(null);
            }
        }

        return $this;
    }
}
