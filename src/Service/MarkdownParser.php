<?php

namespace App\Service;

use Demontpx\ParsedownBundle\Parsedown;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;

class MarkdownParser
{
    /**
     * @var Parsedown
     */
    private Parsedown $parsedown;
    /**
     * @var AdapterInterface
     */
    private AdapterInterface $cache;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var Security
     */
    private Security $security;
    private bool $debug;

    public function __construct(
        Parsedown $parsedown,
        AdapterInterface $cache,
        LoggerInterface $markdownLogger,
        Security $security,
        bool $debug
    ) {

        $this->parsedown = $parsedown;
        $this->cache = $cache;
        $this->logger = $markdownLogger;
        $this->security = $security;
        $this->debug = $debug;
    }

    public function parse(string $source): string
    {
        if (empty($source)) {
            $this->logger->info('Article does not contain content', [
                'user' => $this->security->getUser()->getUsername(),
            ]);
        }

        if ($this->debug) {
            return $this->parsedown->text($source);
        }

        return $this->cache->get(
            'markdown_'.md5($source),
            function () use ($source) {
                return $this->parsedown->text($source);
            }
        );
    }
}