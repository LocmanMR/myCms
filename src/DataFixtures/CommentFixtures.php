<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Service\Interfaces\CommentContentProviderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixtures implements DependentFixtureInterface
{
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

            if (random_int(1,10) <= 7) {
                $comment->setContent($this->commentContentProvider->get('Also', 3));
            } else {
                $comment->setContent($this->commentContentProvider->get());
            }

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
