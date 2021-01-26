<?php
declare(strict_types=1);

namespace App\Service;

use App\Enum\ContentProviderWordsEnum;
use App\Exceptions\ProbabilityException;
use App\Helpers\ProbabilityHelper;
use App\Service\Interfaces\ArticleContentProviderInterface;
use Psr\Log\LoggerInterface;
use Exception;

class ArticleContentProviderService implements ArticleContentProviderInterface
{
    private const PARAGRAPHS = [
        'Lorem ipsum **door** dolor sit amet, consectetur adipiscing elit.',
        'Purus viverra accumsan in nisl. Diam vulputate ut pharetra sit amet aliquam.',
        'Lectus quam id leo in vitae turpis. In eu mi bibendum neque egestas congue.',
        '**map** blandit turpis cursus in hac habitasse platea dictumst quisque.',
        'Tristique et egestas quis ipsum. Consequat semper viverra nam.',
    ];

    private PastWordsService $pastWordsService;
    private LoggerInterface $logger;

    public function __construct(PastWordsService $pastWordsService, LoggerInterface $logger)
    {
        $this->pastWordsService = $pastWordsService;
        $this->logger = $logger;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getContentWithProbability(): string
    {
        try {
            $randomIndex = ProbabilityHelper::getRandomIndex(ContentProviderWordsEnum::CONTENT_WORDS);
            $articleContent = $this->get(
                ContentProviderWordsEnum::CONTENT_WORDS[$randomIndex]['paragraphCount'],
                ContentProviderWordsEnum::CONTENT_WORDS[$randomIndex]['word'],
                ContentProviderWordsEnum::CONTENT_WORDS[$randomIndex]['wordCount']
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
            $articleContent = $this->get(0);
        }

        return $articleContent;
    }

    /**
     * @param int $paragraphs
     * @param string|null $word
     * @param int $wordsCount
     * @return string
     * @throws Exception
     */
    public function get(int $paragraphs, string $word = null, int $wordsCount = 0): string
    {
        if ($paragraphs === 0) {
            return '';
        }

        $content = [];
        for ($count = 0; $count < $paragraphs; $count++) {
            $content[] = self::PARAGRAPHS[array_rand(self::PARAGRAPHS, 1)];
        }
        $content = implode(' ', $content);

        if (!empty($word) && $wordsCount > 0) {
            $content = $this->pastWordsService->paste($content, $word, $wordsCount);
        }

        return $content;
    }

}