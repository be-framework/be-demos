<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Moment;

use Be\Pattern\BlogPublishing\Being\SlugGenerated;
use Be\Pattern\BlogPublishing\Reason\AuthorResolverInterface;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Metadata Resolved - Pure Data Moment (no Potential)
 *
 * This Moment does NOT implement MomentInterface and has no be() method.
 * It is a "Moment" in the Hegelian sense only: a part of the whole
 * (ArticlePublished) that aggregates metadata from the Input
 * and from SlugGenerated (Being).
 *
 * The slug comes from SlugGenerated, while authorId and tags
 * come from the original ArticleInput.
 */
final readonly class MetadataResolved
{
    public string $authorName;
    public string $slug;

    public function __construct(
        #[Inject] SlugGenerated $slugGenerated,
        #[Input] public string $authorId,
        #[Input] public array $tags,
        #[Inject] AuthorResolverInterface $resolver,
    ) {
        $this->slug = $slugGenerated->slug;
        $this->authorName = $resolver->resolve($authorId);
    }
}
