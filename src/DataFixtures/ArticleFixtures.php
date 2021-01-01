<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Exception;

class ArticleFixtures extends BaseFixtures
{
    private static array $articleTitles = [
        'Airflow vs Cron?',
        'Can I program without coffee?',
        'What is the best coffee?',
    ];

    private static array $articleAuthor = [
        'IRR',
        'RRA',
        'IIR',
    ];

    private static array $articleKeywords = [
        'php7.4',
        'Symfony',
        'Nginx',
    ];

    private static array $articleImages = [
        'article-1.jpeg',
        'article-2.jpeg',
        'article-3.jpg',
    ];

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Article::class, 10, function (Article $article) {
            $article
                ->setTitle($this->faker->randomElement(self::$articleTitles))
                ->setDescription('ISD')
                ->setBody("Lorem ipsum **красная точка** dolor sit amet, consectetur adipiscing elit, sed
                    do eiusmod tempor incididunt [Сметанка](/) ut labore et dolore magna aliqua.
                    " . $this->faker->paragraphs($this->faker->numberBetween(2, 5), true)
                );


            if ($this->faker->boolean(60)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $article
                ->setAuthor($this->faker->randomElement(self::$articleAuthor))
                ->setKeywords(implode(
                    ', ',
                    $this->faker->randomElements(self::$articleKeywords, 2, false))
                )
                ->setVoteCount($this->faker->numberBetween(0, 10))
                ->setImageFilename($this->faker->randomElement(self::$articleImages))
            ;
        });
    }

}

