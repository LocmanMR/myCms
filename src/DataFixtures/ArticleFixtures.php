<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Service\Interfaces\ArticleContentProviderInterface;
use App\Service\Interfaces\CommentContentProviderInterface;
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
    private CommentContentProviderInterface $commentContentProvider;

    public function __construct(
        ArticleContentProviderInterface $articleContentProvider,
        CommentContentProviderInterface $commentContentProvider
    ) {
        $this->articleContentProvider = $articleContentProvider;
        $this->commentContentProvider = $commentContentProvider;
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

            for ($i = 0; $i < $this->faker->numberBetween(2, 10); $i++) {
                $this->addComment($article, $manager);
            }
        });
    }

    /**
     * @param Article $article
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function addComment(Article $article, ObjectManager $manager): void
    {
        $comment = (new Comment())
            ->setAuthorName($this->faker->randomElement(self::$articleAuthor))
            ->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 day'))
            ->setArticle($article);

        if (random_int(1,10) <= 7) {
            $comment->setContent($this->commentContentProvider->get('Also', 3));
        } else {
            $comment->setContent($this->commentContentProvider->get());
        }

        if ($this->faker->boolean) {
            $comment->setDeletedAt($this->faker->dateTimeThisMonth);
        }

        $manager->persist($comment);
    }
}

