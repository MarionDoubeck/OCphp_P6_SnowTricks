<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a category for tricks.
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'categoryyy', targetEntity: Trick::class)]
    private Collection $tricks;


    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }


    /**
     * Get the ID of the category.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the name of the category.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the name of the category.
     *
     * @param string $name the name of the category
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Get the description of the category.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * Set the description of the category.
     *
     * @param string $description the descritpion of the category
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }


    /**
     * Get the slug of the category.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }


    /**
     * Set the slug of the category.
     *
     * @param string $slug the slug of the category
     * @return $this
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    
    /**
     * Get the collection of tricks associated with this category.
     *
     * @return Collection<int, Trick>
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }


}
