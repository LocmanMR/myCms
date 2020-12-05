<?php
declare(strict_types=1);


namespace App\Service;


class ArticleProvider
{
    private const ARTICLES = [
        'first' => [
            'title' => 'first article',
            'slug' => 'article-1',
            'image' => 'images/article-1.jpeg',
        ],
        'second' => [
            'title' => 'second article',
            'slug' => 'article-2',
            'image' => 'images/article-2.jpeg',
        ],
        'third' => [
            'title' => 'third article',
            'slug' => 'article-3',
            'image' => 'images/article-3.jpg',
        ],
    ];

    private $markdownParser;

    public function __construct(MarkdownParser $markdownParser)
    {
        $this->markdownParser = $markdownParser;
    }


    public function articles(): array
    {
        return self::ARTICLES;
    }

    public function article(): array
    {
        $articleContent = <<<EOF
Lorem ipsum **красная точка** dolor sit amet, consectetur adipiscing elit, sed
do eiusmod tempor incididunt [Сметанка](/) ut labore et dolore magna aliqua.
Purus viverra accumsan in nisl. Diam vulputate ut pharetra sit amet aliquam. Faucibus a
pellentesque sit amet porttitor eget dolor morbi non. Est ultricies integer quis auctor
elit sed. Tristique nulla aliquet enim tortor at. Tristique et egestas quis ipsum. Consequat semper viverra nam
libero. Lectus quam id leo in vitae turpis. In eu mi bibendum neque egestas congue
quisque egestas diam. **Красная точка** blandit turpis cursus in hac habitasse platea dictumst quisque.
EOF;

        $article = self::ARTICLES[array_rand(self::ARTICLES, 1)];
        $article['articleContent'] = $this->markdownParser->parse($articleContent);

        return $article;
    }

}