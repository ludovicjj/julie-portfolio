<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = ['tag-1', 'tag-2', 'tag-3'];

        foreach ($tags as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
