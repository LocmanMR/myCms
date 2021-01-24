<?php
declare(strict_types=1);

namespace App\Service;


use Exception;

class PastWordsService
{
    private string $wordsMark;

    public function __construct(string $wordsMark)
    {
        $this->wordsMark = $wordsMark;
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
                $explodeContent[$position] .= ' ' . $this->markWords($word);
            } else {
                $explodeContent[$position - 1] .= ' ' . $this->markWords($word);
            }
        }

        return implode(' ', $explodeContent);
    }

    private function markWords(string $word): string
    {
        if ($this->wordsMark === 'bold') {
            return '**' . $word . '**';
        }

        if ($this->wordsMark === 'italics') {
            return '*' . $word . '*';
        }

        return $word;
    }
}
