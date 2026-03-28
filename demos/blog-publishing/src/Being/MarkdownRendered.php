<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Being;

use Be\Demo\BlogPublishing\Reason\MarkdownRenderer;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Markdown Rendered - Being
 *
 * Transforms raw markdown into HTML.
 * The htmlBody produced here flows into ContentPrepared (Moment).
 */
final readonly class MarkdownRendered
{
    public string $htmlBody;

    public function __construct(
        #[Input] public string $markdownBody,
        #[Inject] MarkdownRenderer $renderer,
    ) {
        $this->htmlBody = $renderer->render($markdownBody);
    }
}
