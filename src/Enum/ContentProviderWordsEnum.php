<?php


namespace App\Enum;


class ContentProviderWordsEnum
{
    //probability - вероятность выпадения | 7 из 10 - слово, 3 из 10 - пустое значение
    public const CONTENT_WORDS = [
        ['paragraphCount' => 1, 'word' => '', 'wordCount' => 0, 'probability' => 3],
        ['paragraphCount' => 2, 'word' => 'name', 'wordCount' => 1, 'probability' => 1],
        ['paragraphCount' => 3, 'word' => 'table', 'wordCount' => 2, 'probability' => 1],
        ['paragraphCount' => 4, 'word' => 'phone', 'wordCount' => 3, 'probability' => 1],
        ['paragraphCount' => 5, 'word' => 'cat', 'wordCount' => 4, 'probability' => 1],
        ['paragraphCount' => 6, 'word' => 'clock', 'wordCount' => 5, 'probability' => 1],
        ['paragraphCount' => 7, 'word' => 'coffee', 'wordCount' => 6, 'probability' => 1],
        ['paragraphCount' => 8, 'word' => 'pen', 'wordCount' => 7, 'probability' => 1],
    ];
}