<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Final;

use Be\Demo\BlogPublishing\Moment\ContentPrepared;
use Be\Demo\BlogPublishing\Moment\MetadataResolved;
use Be\Demo\BlogPublishing\Reason\PublishTimestamper;
use Ray\Di\Di\Inject;

/**
 * Article Published - Final (convergence point)
 *
 * Mini-diamond merge: two pure-data Moments converge into one Final.
 * Unlike the order-processing demo, there are NO be() calls here
 * because the Moments have no Potential to realize.
 *
 * The Final simply reads the aggregated data from each Moment
 * and stamps the publication.
 *
 * @link https://schema.org/Article
 */
final readonly class ArticlePublished
{
    public string $articleId;
    public string $publishedAt;

    public function __construct(
        #[Inject] public ContentPrepared $content,
        #[Inject] public MetadataResolved $metadata,
        #[Inject] PublishTimestamper $timestamper,
    ) {
        $this->publishedAt = $timestamper->now();
        $this->articleId = $this->generateArticleId();
    }

    private function generateArticleId(): string
    {
        return sprintf(
            'ART-%s-%s',
            $this->metadata->slug,
            substr(md5($this->metadata->slug . $this->publishedAt), 0, 8)
        );
    }
}
