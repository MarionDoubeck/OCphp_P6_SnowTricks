<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupsFixtures extends Fixture
{
    /** @var int $counter Counter for tracking iterations. */
    private $counter = 1;


    /**
     * Load dummy group data into the database.
     *
     * This function populates the database with sample group data, including group names and descriptions.
     *
     * @param ObjectManager $manager The entity manager to persist the data.
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $group = new Group();
        $group->setName('Grabs');
        $group->setSlug('grabs');
        $group->setDescription('Un grab consiste à attraper la planche avec la main pendant le saut');
        $manager->persist($group);
        $this->addReference('group_'.$this->counter, $group);
        $this->counter++;

        $group = new Group();
        $group->setName('Rotations');
        $group->setSlug('rotations');
        $group->setDescription('On désigne par le mot « rotation » uniquement des rotations horizontales');
        $manager->persist($group);
        $this->addReference('group_'.$this->counter, $group);
        $this->counter++;

        $group = new Group();
        $group->setName('Flips');
        $group->setSlug('flips');
        $group->setDescription('Un flip est une rotation verticale');
        $manager->persist($group);
        $this->addReference('group_'.$this->counter, $group);
        $this->counter++;

        $group = new Group();
        $group->setName('Rotations désaxées');
        $group->setSlug('rotations-desaxees');
        $group->setDescription('Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation.');
        $manager->persist($group);
        $this->addReference('group_'.$this->counter, $group);
        $this->counter++;

        $group = new Group();
        $group->setName('Slides');
        $group->setSlug('slides');
        $group->setDescription('Un slide consiste à glisser sur une barre de slide.');
        $manager->persist($group);
        $this->addReference('group_'.$this->counter, $group);
        $this->counter++;

        $group = new Group();
        $group->setName('One foot tricks');
        $group->setSlug('one-foot-tricks');
        $group->setDescription('Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n\'est pas fixé.');
        $manager->persist($group);
        $this->addReference('group_'.$this->counter, $group);
        $this->counter++;

        $group = new Group();
        $group->setName('Old school');
        $group->setSlug('old-school');
        $group->setDescription('Un ensemble de figures et une manière de réaliser des figures passée de mode, qui fait penser au freestyle des années 1980 - début 1990');
        $manager->persist($group);
        $this->addReference('group_'.$this->counter, $group);
        $this->counter++;

        $manager->flush();
    }
}
