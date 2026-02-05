<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Input;

use Be\Demo\BlogPublishing\Final\ArticlePublished;
use Be\Framework\Attribute\Be;

/**
 * Article Input - Blog Publishing Demo
 *
 * Declares the potential to become ArticlePublished.
 * The input splits into two Beings (MarkdownRendered, SlugGenerated)
 * which feed two pure-data Moments (ContentPrepared, MetadataResolved)
 * before merging in the Final.
 *
 * @link https://schema.org/Article
 */
#[Be([ArticlePublished::class])]
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
