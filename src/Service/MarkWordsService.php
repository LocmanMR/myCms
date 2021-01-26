<?php


namespace App\Service;


use App\Service\Interfaces\MarkWordsInterface;

class MarkWordsService implements MarkWordsInterface
{
    private string $wordMark;

    public function __construct(string $wordMark)
    {
        $this->wordMark = $wordMark;
    }

    public function markWords(string $word): string
    {
        if ($this->wordMark === 'bold') {
            return '**' . $word . '**';
        }

        if ($this->wordMark === 'italics') {
            return '*' . $word . '*';
        }

        return $word;
    }
}
