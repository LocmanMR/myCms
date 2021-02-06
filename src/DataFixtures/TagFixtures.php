<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Tag::class, 10, function (Tag $tag) {
            $tag->setName($this->faker->realText(15));

            if (random_int(1,10) <= 3) {
                $tag->setDeletedAt($this->faker->dateTimeThisMonth);
            }
        });

        $manager->flush();
    }
}
