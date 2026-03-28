<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Module;

use Be\Demo\ContactForm\Reason\EmailNormalizer;
use Be\Demo\ContactForm\Reason\ReceiptGenerator;
use Be\Framework\Module\BeModule;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Install BeModule with demo's semantic namespace
        $this->install(new BeModule('Be\Demo\ContactForm\Semantic'));

        $this->bind(EmailNormalizer::class);
        $this->bind(ReceiptGenerator::class);
    }
}
