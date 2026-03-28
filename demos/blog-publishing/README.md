# Blog Publishing Demo

**Level:** Intermediate
**Concepts:** Being transformation with multiple Reason dependencies

## Flow

```
ArticleInput → ArticlePrepared(Being) → ArticlePublished(Final)
```

## Key Concept: Being with Multiple Transformations

This demo shows how a single Being can orchestrate multiple transformations using different Reason services:

- **Markdown rendering** - Converts markdown to HTML
- **Slug generation** - Creates URL-friendly slugs from titles
- **Excerpt extraction** - Pulls excerpt from rendered content
- **Author resolution** - Resolves author ID to author name

All these transformations happen in the Being layer, preparing the data for the Final state.

## The Code

### Input (Potentiality)

```php
#[Be([ArticlePrepared::class])]
final readonly class ArticleInput
{
    public function __construct(
        public string $title,
        public string $markdownBody,
        public string $authorId,
        public array $tags,
    ) {}
}
```

### Being (Transformation)

```php
#[Be([ArticlePublished::class])]
final readonly class ArticlePrepared
{
    public string $htmlBody;
    public string $slug;
    public string $excerpt;
    public string $authorName;

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
```

### Final (Actuality)

```php
final readonly class ArticlePublished
{
    public string $articleId;
    public string $publishedAt;

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
}
```

## Layer Breakdown

- **Input:** ArticleInput (title, markdownBody, authorId, tags)
- **Being:** ArticlePrepared (renders HTML, generates slug, extracts excerpt, resolves author)
- **Final:** ArticlePublished (stamps publication with ID and timestamp)
- **Semantic:** Title, MarkdownBody, AuthorId, Tag, HtmlBody, Slug, Excerpt, AuthorName (validation)
- **Reason:** MarkdownRenderer, SlugGenerator, ExcerptExtractor, AuthorResolver, PublishTimestamper
