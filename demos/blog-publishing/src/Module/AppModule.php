<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Module;

use Be\Demo\BlogPublishing\Reason\AuthorResolver;
use Be\Demo\BlogPublishing\Reason\AuthorResolverInterface;
use Be\Demo\BlogPublishing\Reason\ExcerptExtractor;
use Be\Demo\BlogPublishing\Reason\MarkdownRenderer;
use Be\Demo\BlogPublishing\Reason\PublishTimestamper;
use Be\Demo\BlogPublishing\Reason\SlugGenerator;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Reason bindings
        $this->bind(MarkdownRenderer::class);
        $this->bind(SlugGenerator::class);
        $this->bind(ExcerptExtractor::class);
        $this->bind(AuthorResolverInterface::class)->to(AuthorResolver::class);
        $this->bind(PublishTimestamper::class);
    }
}
