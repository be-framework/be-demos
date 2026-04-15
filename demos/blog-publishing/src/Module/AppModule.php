<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Module;

use Be\Pattern\BlogPublishing\Reason\AuthorResolver;
use Be\Pattern\BlogPublishing\Reason\AuthorResolverInterface;
use Be\Pattern\BlogPublishing\Reason\ExcerptExtractor;
use Be\Pattern\BlogPublishing\Reason\MarkdownRenderer;
use Be\Pattern\BlogPublishing\Reason\PublishTimestamper;
use Be\Pattern\BlogPublishing\Reason\SlugGenerator;
use Be\Framework\Module\BeModule;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Install BeModule with demo's semantic namespace
        $this->install(new BeModule('Be\Pattern\BlogPublishing\Semantic'));

        // Reason bindings
        $this->bind(MarkdownRenderer::class);
        $this->bind(SlugGenerator::class);
        $this->bind(ExcerptExtractor::class);
        $this->bind(AuthorResolverInterface::class)->to(AuthorResolver::class);
        $this->bind(PublishTimestamper::class);
    }
}
