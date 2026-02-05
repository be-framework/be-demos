<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Moment;

use Be\Demo\BlogPublishing\Reason\ExcerptExtractor;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Content Prepared - Pure Data Moment (no Potential)
 *
 * This Moment does NOT implement MomentInterface and has no be() method.
 * It is a "Moment" in the Hegelian sense only: a part of the whole
 * (ArticlePublished) that aggregates content data from the Input
 * and from MarkdownRendered (Being).
 *
 * Without Potential, there is nothing to "realize" - the data is
 * simply gathered and made available to the Final.
 */
final readonly class ContentPrepared
{
    public string $excerpt;

    public function __construct(
        #[Input] public string $title,
        #[Input] public string $markdownBody,
        #[Input] public string $htmlBody,
        #[Inject] ExcerptExtractor $extractor,
    ) {
        $this->excerpt = $extractor->extract($htmlBody);
    }
}
