<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Being;

use Be\Pattern\BlogPublishing\Final\ArticlePublished;
use Be\Pattern\BlogPublishing\Reason\AuthorResolverInterface;
use Be\Pattern\BlogPublishing\Reason\ExcerptExtractor;
use Be\Pattern\BlogPublishing\Reason\MarkdownRenderer;
use Be\Pattern\BlogPublishing\Reason\SlugGenerator;
use Be\Framework\Attribute\Be;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Article Prepared - Being (transformation)
 *
 * Transforms raw article input into prepared content:
 * - Renders markdown to HTML
 * - Generates URL slug from title
 * - Extracts excerpt from content
 * - Resolves author name
 *
 * All properties flow to ArticlePublished Final.
 */
#[Be([ArticlePublished::class])]
final readonly class ArticlePrepared
{
    public string $htmlBody;
    public string $slug;
    public string $excerpt;
    public string $authorName;

    /**
     * @param string[] $tags
     */
    public function __construct(
        #[Input] public string $title,
        #[Input] public string $markdownBody,
        #[Input] public string $authorId,
        #[Input] public array $tags,
        #[Inject] MarkdownRenderer $markdownRenderer,
        #[Inject] SlugGenerator $slugGenerator,
        #[Inject] ExcerptExtractor $excerptExtractor,
        #[Inject] AuthorResolverInterface $authorResolver,
    ) {
        $this->htmlBody = $markdownRenderer->render($markdownBody);
        $this->slug = $slugGenerator->generate($title);
        $this->excerpt = $excerptExtractor->extract($this->htmlBody);
        $this->authorName = $authorResolver->resolve($authorId);
    }
}
