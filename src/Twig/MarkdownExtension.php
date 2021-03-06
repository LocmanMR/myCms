<?php
declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MarkdownExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('cached_markdown', [MarkdownExtensionRuntime::class, 'parseMarkdown'], ['is_safe' => ['html']]),
        ];
    }
}
