<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Service\Interfaces\CommentContentProviderInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixtures
{
    private CommentContentProviderInterface $commentContentProvider;
    private ArticleRepository $articleRepository;

    public function __construct(
        CommentContentProviderInterface $commentContentProvider,
        ArticleRepository $articleRepository
    ) {
        $this->commentContentProvider = $commentContentProvider;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $articles = $this->articleRepository->findLatestPublished();
        foreach ($articles as $article) {
            $this->createMany(Comment::class, 5, function (Comment $comment) use ($article) {
                $comment
                    ->setAuthorName('IRR')
                    ->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 day'))
                    ->setArticle($article);

                if (random_int(1,10) > 7) {
                    $comment->setContent($this->commentContentProvider->get('Also', 3));
                } else {
                    $comment->setContent($this->commentContentProvider->get());
                }

                if ($this->faker->boolean) {
                    $comment->setDeletedAt($this->faker->dateTimeThisMonth);
                }
            });
        }
    }
}
