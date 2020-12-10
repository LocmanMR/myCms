<?php
declare(strict_types=1);


namespace App\Service;


use App\Service\Interfaces\ArticleContentProviderInterface;
use App\Exceptions\ProbabilityException;
use App\Helpers\ProbabilityHelper;
use Psr\Log\LoggerInterface;

class ArticleProvider
{
    private const ARTICLES = [
        'first' => [
            'title' => 'first article',
            'slug' => 'article-1',
            'image' => 'images/article-1.jpeg',
        ],
        'second' => [
            'title' => 'second article',
            'slug' => 'article-2',
            'image' => 'images/article-2.jpeg',
        ],
        'third' => [
            'title' => 'third article',
            'slug' => 'article-3',
            'image' => 'images/article-3.jpg',
        ],
    ];

    private ArticleContentProviderInterface $articleContentProvider;
    private LoggerInterface $logger;

    public function __construct(
        ArticleContentProviderInterface $articleContentProvider,
        LoggerInterface $logger
    ) {
        $this->articleContentProvider = $articleContentProvider;
        $this->logger = $logger;
    }

    public function articles(): array
    {
        return self::ARTICLES;
    }

    public function article(): array
    {
        $article = self::ARTICLES[array_rand(self::ARTICLES, 1)];
        $article['articleContent'] = $this->getArticleContent();

        return $article;
    }

    private function getArticleContent(): string
    {
        //probability - вероятность выпадения | 7 из 10 - слово, 3 из 10 - пустое значение
        $words = [
            ['paragraphCount' => 1, 'word' => '', 'wordCount' => 0, 'probability' => 3],
            ['paragraphCount' => 2, 'word' => 'name', 'wordCount' => 1, 'probability' => 1],
            ['paragraphCount' => 3, 'word' => 'table', 'wordCount' => 2, 'probability' => 1],
            ['paragraphCount' => 4, 'word' => 'phone', 'wordCount' => 3, 'probability' => 1],
            ['paragraphCount' => 5, 'word' => 'cat', 'wordCount' => 4, 'probability' => 1],
            ['paragraphCount' => 6, 'word' => 'clock', 'wordCount' => 5, 'probability' => 1],
            ['paragraphCount' => 7, 'word' => 'coffee', 'wordCount' => 6, 'probability' => 1],
            ['paragraphCount' => 8, 'word' => 'pen', 'wordCount' => 7, 'probability' => 1],
        ];

        try {
            $randomIndex = ProbabilityHelper::getRandomIndex($words);
            $articleContent = $this->articleContentProvider->get(
                $words[$randomIndex]['paragraphCount'],
                $words[$randomIndex]['word'],
                $words[$randomIndex]['wordCount']
            );
        } catch (ProbabilityException $e) {
            $this->logger->warning(
                'Probability helper failed',
                [
                    'Exception' => $e->getExceptionClass(),
                    'Message' => $e->getMessage(),
                    'Trace' => $e->getTrace(),
                ]
            );
            $articleContent = $this->articleContentProvider->get(0);
        }

        return $articleContent;
    }

}