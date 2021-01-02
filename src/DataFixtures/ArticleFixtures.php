<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Service\ArticleProvider;
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

    private ArticleProvider $articleProvider;

    public function __construct(ArticleProvider $articleProvider)
    {
        $this->articleProvider = $articleProvider;
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Article::class, 10, function (Article $article) use ($manager) {
            $article
                ->setTitle($this->faker->randomElement(self::$articleTitles))
                ->setDescription('ISD')
                ->setBody($this->articleProvider->getArticleContent());

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

            for ($i = 0; $i < 3; $i++) {
                $comment = (new Comment())
                    ->setAuthorName($this->faker->randomElement(self::$articleAuthor))
                    ->setContent($this->faker->paragraph)
                    ->setArticle($article)
                ;

                $manager->persist($comment);
            }
        });
    }

}

