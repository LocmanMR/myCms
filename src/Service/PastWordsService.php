<?php
declare(strict_types=1);

namespace App\Service;

use Exception;

class PastWordsService
{
    private MarkWordsService $markWordsService;

    public function __construct(MarkWordsService $markWordsService)
    {
        $this->markWordsService = $markWordsService;
    }

    /**
     * @param string $content
     * @param string $word
     * @param int $wordsCount
     * @return string
     * @throws Exception
     */
    public function paste(string $content, string $word, int $wordsCount): string
    {
        $explodeContent = explode(' ', $content);
        $wordsInContext = count($explodeContent);
        for ($count = 0; $count < $wordsCount; $count++) {
            $position = random_int(0, $wordsInContext);
            if (array_key_exists($position, $explodeContent)) {
                $explodeContent[$position] .= ' ' . $this->markWordsService->markWords($word);
            } else {
                $explodeContent[$position - 1] .= ' ' . $this->markWordsService->markWords($word);
            }
        }

        return implode(' ', $explodeContent);
    }
}
