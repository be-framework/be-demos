<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Tests;

use Be\Demo\BlogPublishing\Final\ArticlePublished;
use Be\Demo\BlogPublishing\Input\ArticleInput;
use Be\Demo\BlogPublishing\Module\AppModule;
use Be\Framework\Becoming;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class BlogPublishingTest extends TestCase
{
    private Becoming $becoming;

    protected function setUp(): void
    {
        $injector = new Injector(new AppModule());
        $this->becoming = $injector->getInstance(Becoming::class);
    }

    public function testArticleInputBecomesArticlePublished(): void
    {
        $input = new ArticleInput(
            title: 'Understanding the BE Framework',
            markdownBody: 'The BE Framework brings **Hegelian philosophy** to software architecture. '
                . 'It models transformations as a process of *becoming*, where Input evolves through '
                . 'Being and Moment stages to reach its Final state.',
            authorId: '550e8400-e29b-41d4-a716-446655440000',
            tags: ['philosophy', 'framework', 'php'],
        );

        /** @var ArticlePublished $final */
        $final = ($this->becoming)($input);

        $this->assertInstanceOf(ArticlePublished::class, $final);
        $this->assertStringStartsWith('ART-', $final->articleId);
        $this->assertNotEmpty($final->publishedAt);

        // Verify content Moment data is accessible (pure data, no be() needed)
        $this->assertSame('Understanding the BE Framework', $final->content->title);
        $this->assertStringContainsString('<strong>Hegelian philosophy</strong>', $final->content->htmlBody);
        $this->assertNotEmpty($final->content->excerpt);

        // Verify metadata Moment data is accessible (pure data, no be() needed)
        $this->assertSame('understanding-the-be-framework', $final->metadata->slug);
        $this->assertSame('550e8400-e29b-41d4-a716-446655440000', $final->metadata->authorId);
        $this->assertSame(['philosophy', 'framework', 'php'], $final->metadata->tags);
        $this->assertStringStartsWith('Author-', $final->metadata->authorName);
    }

    public function testArticleWithHeadings(): void
    {
        $input = new ArticleInput(
            title: 'Markdown Rendering Test',
            markdownBody: '# Introduction'
                . "\n\n"
                . 'This is the first paragraph of the article body which must be long enough '
                . 'to pass the semantic validation of minimum fifty characters.',
            authorId: '660e8400-e29b-41d4-a716-446655440000',
            tags: ['test'],
        );

        /** @var ArticlePublished $final */
        $final = ($this->becoming)($input);

        $this->assertInstanceOf(ArticlePublished::class, $final);
        $this->assertStringContainsString('<h1>Introduction</h1>', $final->content->htmlBody);
        $this->assertSame('markdown-rendering-test', $final->metadata->slug);
    }

    public function testExcerptIsTruncated(): void
    {
        $longBody = str_repeat('This is a sentence for testing excerpt extraction. ', 20);

        $input = new ArticleInput(
            title: 'Long Article Excerpt Test',
            markdownBody: $longBody,
            authorId: '770e8400-e29b-41d4-a716-446655440000',
            tags: ['long-form'],
        );

        /** @var ArticlePublished $final */
        $final = ($this->becoming)($input);

        // Excerpt should be truncated to 200 chars + "..."
        $this->assertLessThanOrEqual(203, mb_strlen($final->content->excerpt));
        $this->assertStringEndsWith('...', $final->content->excerpt);
    }

    // ──────────────────────────────────────────────
    // Negative Test Cases
    // ──────────────────────────────────────────────

    public function testEmptyTitleThrowsException(): void
    {
        $this->expectException(\Be\Demo\BlogPublishing\Exception\InvalidTitleException::class);

        $input = new ArticleInput(
            title: '',
            markdownBody: 'This is the body content that is long enough to pass the minimum 50 character validation.',
            authorId: '550e8400-e29b-41d4-a716-446655440000',
            tags: ['test'],
        );

        ($this->becoming)($input);
    }

    public function testTitleTooLongThrowsException(): void
    {
        $this->expectException(\Be\Demo\BlogPublishing\Exception\InvalidTitleException::class);

        $input = new ArticleInput(
            title: str_repeat('a', 201),
            markdownBody: 'This is the body content that is long enough to pass the minimum 50 character validation.',
            authorId: '550e8400-e29b-41d4-a716-446655440000',
            tags: ['test'],
        );

        ($this->becoming)($input);
    }

    public function testBodyTooShortThrowsException(): void
    {
        $this->expectException(\Be\Demo\BlogPublishing\Exception\InvalidBodyException::class);

        $input = new ArticleInput(
            title: 'Valid Title',
            markdownBody: 'Too short body.',
            authorId: '550e8400-e29b-41d4-a716-446655440000',
            tags: ['test'],
        );

        ($this->becoming)($input);
    }

    public function testBodyTooLongThrowsException(): void
    {
        $this->expectException(\Be\Demo\BlogPublishing\Exception\InvalidBodyException::class);

        $input = new ArticleInput(
            title: 'Valid Title',
            markdownBody: str_repeat('a', 50001),
            authorId: '550e8400-e29b-41d4-a716-446655440000',
            tags: ['test'],
        );

        ($this->becoming)($input);
    }

    public function testInvalidAuthorIdThrowsException(): void
    {
        $this->expectException(\Be\Demo\BlogPublishing\Exception\InvalidAuthorException::class);

        $input = new ArticleInput(
            title: 'Valid Title',
            markdownBody: 'This is the body content that is long enough to pass the minimum 50 character validation.',
            authorId: 'not-a-valid-uuid',
            tags: ['test'],
        );

        ($this->becoming)($input);
    }

    public function testInvalidTagFormatThrowsException(): void
    {
        $this->expectException(\Be\Demo\BlogPublishing\Exception\InvalidTagException::class);

        $input = new ArticleInput(
            title: 'Valid Title',
            markdownBody: 'This is the body content that is long enough to pass the minimum 50 character validation.',
            authorId: '550e8400-e29b-41d4-a716-446655440000',
            tags: ['INVALID_TAG'],
        );

        ($this->becoming)($input);
    }

    public function testTagTooLongThrowsException(): void
    {
        $this->expectException(\Be\Demo\BlogPublishing\Exception\InvalidTagException::class);

        $input = new ArticleInput(
            title: 'Valid Title',
            markdownBody: 'This is the body content that is long enough to pass the minimum 50 character validation.',
            authorId: '550e8400-e29b-41d4-a716-446655440000',
            tags: [str_repeat('a', 31)],
        );

        ($this->becoming)($input);
    }
}
