<?php
declare(strict_types=1);

namespace App\Service;

use App\Service\Interfaces\CommentContentProviderInterface;
use Exception;

class CommentContentProviderService implements CommentContentProviderInterface
{
    private const COMMENT_PARAGRAPHS = [
        'Comment 1. I look forward to continuing!',
        'Comment 2. Controversial decision.',
        'Comment 3. Need to be finalized.',
        'Comment 4. Great job.',
        'Comment 5. Perfect.',
    ];

    private PastWordsService $pastWordsService;

    public function __construct(PastWordsService $pastWordsService)
    {
        $this->pastWordsService = $pastWordsService;
    }

    /**
     * @param string|null $word
     * @param int $wordsCount
     * @return string
     * @throws Exception
     */
    public function get(string $word = null, int $wordsCount = 0): string
    {
        $comment = self::COMMENT_PARAGRAPHS[array_rand(self::COMMENT_PARAGRAPHS, 1)];

        if (!empty($word) && $wordsCount > 0) {
            $comment = $this->pastWordsService->paste($comment, $word, $wordsCount);
        }

        return $comment;
    }
}
