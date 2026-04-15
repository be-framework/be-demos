<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Module;

use Be\Pattern\ContactForm\Reason\EmailNormalizer;
use Be\Pattern\ContactForm\Reason\ReceiptGenerator;
use Be\Framework\Module\BeModule;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Install BeModule with demo's semantic namespace
        $this->install(new BeModule('Be\Pattern\ContactForm\Semantic'));

        $this->bind(EmailNormalizer::class);
        $this->bind(ReceiptGenerator::class);
    }
}
