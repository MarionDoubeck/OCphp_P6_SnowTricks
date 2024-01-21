<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    /** @var int $counter Counter for tracking iterations. */
    private $counter = 1;


    /**
     * Load dummy category data into the database.
     *
     * This function populates the database with sample category data, including category names and descriptions.
     *
     * @param ObjectManager $manager The entity manager to persist the data.
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Grabs');
        $category->setSlug('grabs');
        $category->setDescription('Un grab consiste à attraper la planche avec la main pendant le saut');
        $manager->persist($category);
        $this->addReference('category_'.$this->counter, $category);
        $this->counter++;

        $category = new Category();
        $category->setName('Rotations');
        $category->setSlug('rotations');
        $category->setDescription('On désigne par le mot « rotation » uniquement des rotations horizontales');
        $manager->persist($category);
        $this->addReference('category_'.$this->counter, $category);
        $this->counter++;

        $category = new Category();
        $category->setName('Flips');
        $category->setSlug('flips');
        $category->setDescription('Un flip est une rotation verticale');
        $manager->persist($category);
        $this->addReference('category_'.$this->counter, $category);
        $this->counter++;

        $category = new Category();
        $category->setName('Rotations désaxées');
        $category->setSlug('rotations-desaxees');
        $category->setDescription('Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation.');
        $manager->persist($category);
        $this->addReference('category_'.$this->counter, $category);
        $this->counter++;

        $category = new Category();
        $category->setName('Slides');
        $category->setSlug('slides');
        $category->setDescription('Un slide consiste à glisser sur une barre de slide.');
        $manager->persist($category);
        $this->addReference('category_'.$this->counter, $category);
        $this->counter++;

        $category = new Category();
        $category->setName('One foot tricks');
        $category->setSlug('one-foot-tricks');
        $category->setDescription('Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n\'est pas fixé.');
        $manager->persist($category);
        $this->addReference('category_'.$this->counter, $category);
        $this->counter++;

        $category = new Category();
        $category->setName('Old school');
        $category->setSlug('old-school');
        $category->setDescription('Un ensemble de figures et une manière de réaliser des figures passée de mode, qui fait penser au freestyle des années 1980 - début 1990');
        $manager->persist($category);
        $this->addReference('category_'.$this->counter, $category);
        $this->counter++;

        $manager->flush();
    }

    
}
