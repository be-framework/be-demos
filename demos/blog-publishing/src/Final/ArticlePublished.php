<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Final;

use Be\Pattern\BlogPublishing\Reason\PublishTimestamper;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Article Published - Final (actualization)
 *
 * Receives all prepared content from ArticlePrepared Being
 * and stamps the publication with ID and timestamp.
 *
 * @link https://schema.org/Article
 */
final readonly class ArticlePublished
{
    public string $articleId;
    public string $publishedAt;

    /**
     * @param string[] $tags
     */
    public function __construct(
        #[Input] public string $title,
        #[Input] public string $htmlBody,
        #[Input] public string $slug,
        #[Input] public string $excerpt,
        #[Input] public string $authorId,
        #[Input] public string $authorName,
        #[Input] public array $tags,
        #[Inject] PublishTimestamper $timestamper,
    ) {
        $this->publishedAt = $timestamper->now();
        $this->articleId = $this->generateArticleId();
    }

    private function generateArticleId(): string
    {
        return sprintf(
            'ART-%s-%s',
            $this->slug,
            substr(md5($this->slug . $this->publishedAt), 0, 8)
        );
    }
}
