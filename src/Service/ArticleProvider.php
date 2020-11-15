<?php
declare(strict_types=1);


namespace App\Service;


use Symfony\Component\HttpFoundation\Response;

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

    public function articles(): array
    {
        return self::ARTICLES;
    }

    public function article(): array
    {
        return self::ARTICLES[array_rand(self::ARTICLES, 1)];
    }

}