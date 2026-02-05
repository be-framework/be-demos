<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Module;

use Be\Demo\ContactForm\Reason\EmailNormalizer;
use Be\Demo\ContactForm\Reason\ReceiptGenerator;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(EmailNormalizer::class);
        $this->bind(ReceiptGenerator::class);
    }
}
