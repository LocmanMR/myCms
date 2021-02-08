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

            $content = $this->commentContentProvider->get();
            if (random_int(1, 10) <= 7) {
                $content = $this->commentContentProvider->get(
                    self::COMMENT_WORDS[array_rand(self::COMMENT_WORDS, 1)],
                    $this->faker->numberBetween(0, 5)
                );
            }
            $comment->setContent($content);

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
