<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Service\Interfaces\CommentContentProviderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private const COMMENT_WORDS = [
        'Also',
        'Head',
        'Strange',
        'Golang',
    ];

    /**
     * @var CommentContentProviderInterface
     */
    private CommentContentProviderInterface $commentContentProvider;

    public function __construct(CommentContentProviderInterface $commentContentProvider)
    {
        $this->commentContentProvider = $commentContentProvider;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Comment::class, 100, function (Comment $comment) {
            $comment
                ->setAuthorName($this->faker->name)
                ->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 day'))
                ->setArticle($this->getRandomReference(Article::class));

            $contentWord = null;
            $wordCount = 0;
            if (random_int(1, 10) <= 7) {
                $contentWord = self::COMMENT_WORDS[array_rand(self::COMMENT_WORDS, 1)];
                $wordCount = $this->faker->numberBetween(1, 5);
            }
            $comment->setContent($this->commentContentProvider->get($contentWord, $wordCount));

            if ($this->faker->boolean) {
                $comment->setDeletedAt($this->faker->dateTimeThisMonth);
            }
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ArticleFixtures::class,
        ];
    }
}
