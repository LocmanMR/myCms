<?php


namespace App\Service\Interfaces;


interface CommentContentProviderInterface
{
    public function get(string $word = null, int $wordsCount = 0): string;
}