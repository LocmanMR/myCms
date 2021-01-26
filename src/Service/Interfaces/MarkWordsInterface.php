<?php
declare(strict_types=1);

namespace App\Service\Interfaces;

interface MarkWordsInterface
{
    public function markWords(string $word): string;
}
