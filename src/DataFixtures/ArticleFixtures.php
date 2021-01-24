<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Service\Interfaces\ArticleContentProviderInterface;
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

    private ArticleContentProviderInterface $articleContentProvider;

    public function __construct(ArticleContentProviderInterface $articleContentProvider)
    {
        $this->articleContentProvider = $articleContentProvider;
    }

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
                ->setBody($this->articleContentProvider->getContentWithProbability());

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

