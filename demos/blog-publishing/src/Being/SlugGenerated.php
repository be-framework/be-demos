<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Being;

use Be\Pattern\BlogPublishing\Reason\SlugGenerator;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Slug Generated - Being
 *
 * Transforms the article title into a URL-friendly slug.
 * The slug produced here flows into MetadataResolved (Moment).
 */
final readonly class SlugGenerated
{
    public string $slug;

    public function __construct(
        #[Input] public string $title,
        #[Inject] SlugGenerator $generator,
    ) {
        $this->slug = $generator->generate($title);
    }
}
