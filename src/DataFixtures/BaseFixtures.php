<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use RuntimeException;

abstract class BaseFixtures extends Fixture
{
    protected Generator $faker;
    protected ObjectManager $manager;
    private array $referencesIndex = [];

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        $this->loadData($manager);
    }

    abstract public function loadData(ObjectManager $manager): void;

    protected function create(string $className, callable $factory): object
    {
        $entity = new $className();
        $factory($entity);

        $this->manager->persist($entity);

        return $entity;
    }

    protected function createMany(string $className, int $count, callable $factory): void
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = $this->create($className, $factory);

            $this->addReference("$className|$i", $entity);
        }

        $this->manager->flush();
    }

    /**
     * @param $className
     * @return Object
     */
    protected function getRandomReference($className): Object
    {
        if (! isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $reference) {
                if (strpos($key, $className . '|') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }

        if (empty($this->referencesIndex[$className])) {
            throw new RuntimeException('Class references not found: ' . $className);
        }

        return $this->getReference($this->faker->randomElement($this->referencesIndex[$className]));
    }


}

