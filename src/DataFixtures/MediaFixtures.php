<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Media;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{


    /**
     * Load dummy media data into the database.
     *
     * This function generates fake media data to simulate the addition of images and videos.
     * Media types, descriptions, and paths are randomized. Images are retrieved with image URLs,
     * and video URLs are generated in the format of a YouTube video link.
     *
     * @param ObjectManager $manager The entity manager to persist the data.
     * 
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $types = ['image', 'video'];

        for ($i = 1; $i <= 100; $i++) {
            $media = new Media();
            $type = $types[rand(0,1)];
            $media->setType($type);
            $media->setDescription($faker->text(20));
            if($type === 'image') {
                $path = 'fakeBbdImg.webp';
            } else {
                $path = '<iframe width="560" height="315" src="https://www.youtube.com/embed/SQyTWk7OxSI?si=PeFcG8WF59DBgnwR" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
            }

            $media->setPath($path);
            $trick = $this->getReference('trick_'.rand(1,10));
            $media->setTrick($trick);
            $manager->persist($media);
        }

        $manager->flush();

    }//end load()


    /**
     * Get the dependencies for this fixture.
     *
     * This function returns an array of fixture classes that this fixture depends on.
     *
     * @return array
     */
    public function getDependencies():array
    {
        return [
            TricksFixtures::class
        ];
    }


}
