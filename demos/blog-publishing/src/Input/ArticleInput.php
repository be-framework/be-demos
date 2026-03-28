<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Input;

use Be\Demo\BlogPublishing\Being\ArticlePrepared;
use Be\Framework\Attribute\Be;

/**
 * Article Input - Blog Publishing Demo
 *
 * Declares the potential to become ArticlePrepared (Being).
 * The Being transforms content and metadata before
 * flowing to the Final (ArticlePublished).
 *
 * @link https://schema.org/Article
 */
#[Be([ArticlePrepared::class])]
final readonly class ArticleInput
{
    /**
     * @param string[] $tags
     */
    public function __construct(
        public string $title,
        public string $markdownBody,
        public string $authorId,
        public array $tags,
    ) {
    }
}
