<?php
declare(strict_types=1);


namespace App\Service;


use App\Service\Interfaces\ArticleContentProviderInterface;

class ArticleContentProviderService implements ArticleContentProviderInterface
{
    private const PARAGRAPHS = [
        'Lorem ipsum **door** dolor sit amet, consectetur adipiscing elit.',
        'Purus viverra accumsan in nisl. Diam vulputate ut pharetra sit amet aliquam.',
        'Lectus quam id leo in vitae turpis. In eu mi bibendum neque egestas congue.',
        '**map** blandit turpis cursus in hac habitasse platea dictumst quisque.',
        'Tristique et egestas quis ipsum. Consequat semper viverra nam.',
    ];

    private string $wordsMark;

    public function __construct(string $wordsMark)
    {
        $this->wordsMark = $wordsMark;
    }

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
            $content = $this->setWordsToContent($content, $word, $wordsCount);
        }

        return $content;
    }

    private function setWordsToContent(string $content, string $word, int $wordsCount): string
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