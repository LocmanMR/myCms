<?php


namespace App\Service\Interfaces;


interface ArticleContentProviderInterface
{
    public function get(int $paragraphs, string $word = null, int $wordsCount = 0): string;
}