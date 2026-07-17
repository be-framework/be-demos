<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Tests;

use Be\Pattern\BlogPublishing\Exception\InvalidAuthorException;
use Be\Pattern\BlogPublishing\Exception\InvalidBodyException;
use Be\Pattern\BlogPublishing\Exception\InvalidTagException;
use Be\Pattern\BlogPublishing\Exception\InvalidTitleException;
use Be\Pattern\BlogPublishing\Final\ArticlePublished;
use Be\Pattern\BlogPublishing\Input\ArticleInput;
use Be\Pattern\BlogPublishing\Module\AppModule;
use Be\Pattern\BlogPublishing\Semantic\AuthorId;
use Be\Pattern\BlogPublishing\Semantic\MarkdownBody;
use Be\Pattern\BlogPublishing\Semantic\Tags;
use Be\Pattern\BlogPublishing\Semantic\Title;
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

        // Verify content data
        $this->assertSame('Understanding the BE Framework', $final->title);
        $this->assertStringContainsString('<strong>Hegelian philosophy</strong>', $final->htmlBody);
        $this->assertNotEmpty($final->excerpt);

        // Verify metadata
        $this->assertSame('understanding-the-be-framework', $final->slug);
        $this->assertSame('550e8400-e29b-41d4-a716-446655440000', $final->authorId);
        $this->assertSame(['philosophy', 'framework', 'php'], $final->tags);
        $this->assertStringStartsWith('Author-', $final->authorName);
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
        $this->assertStringContainsString('<h1>Introduction</h1>', $final->htmlBody);
        $this->assertSame('markdown-rendering-test', $final->slug);
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
        $this->assertLessThanOrEqual(203, mb_strlen($final->excerpt));
        $this->assertStringEndsWith('...', $final->excerpt);
    }

    // ──────────────────────────────────────────────
    // Semantic Validation Tests
    // ──────────────────────────────────────────────

    public function testValidTitle(): void
    {
        $semantic = new Title();
        $semantic->validate('Understanding the BE Framework');
        $this->addToAssertionCount(1);
    }

    public function testEmptyTitleThrowsException(): void
    {
        $this->expectException(InvalidTitleException::class);
        $semantic = new Title();
        $semantic->validate('');
    }

    public function testTitleTooLongThrowsException(): void
    {
        $this->expectException(InvalidTitleException::class);
        $semantic = new Title();
        $semantic->validate(str_repeat('a', 201));
    }

    public function testValidMarkdownBody(): void
    {
        $semantic = new MarkdownBody();
        $semantic->validate('This is the body content that is long enough to pass the minimum 50 character validation.');
        $this->addToAssertionCount(1);
    }

    public function testBodyTooShortThrowsException(): void
    {
        $this->expectException(InvalidBodyException::class);
        $semantic = new MarkdownBody();
        $semantic->validate('Too short body.');
    }

    public function testBodyTooLongThrowsException(): void
    {
        $this->expectException(InvalidBodyException::class);
        $semantic = new MarkdownBody();
        $semantic->validate(str_repeat('a', 50001));
    }

    public function testValidAuthorId(): void
    {
        $semantic = new AuthorId();
        $semantic->validate('550e8400-e29b-41d4-a716-446655440000');
        $this->addToAssertionCount(1);
    }

    public function testInvalidAuthorIdThrowsException(): void
    {
        $this->expectException(InvalidAuthorException::class);
        $semantic = new AuthorId();
        $semantic->validate('not-a-valid-uuid');
    }

    public function testValidTags(): void
    {
        $semantic = new Tags();
        $semantic->validate(['philosophy', 'framework', 'php']);
        $this->addToAssertionCount(1);
    }

    public function testEmptyTagsThrowsException(): void
    {
        $this->expectException(InvalidTagException::class);
        $semantic = new Tags();
        $semantic->validate([]);
    }

    public function testInvalidTagFormatThrowsException(): void
    {
        $this->expectException(InvalidTagException::class);
        $semantic = new Tags();
        $semantic->validate(['INVALID_TAG']);
    }

    public function testTagTooLongThrowsException(): void
    {
        $this->expectException(InvalidTagException::class);
        $semantic = new Tags();
        $semantic->validate([str_repeat('a', 31)]);
    }
}
