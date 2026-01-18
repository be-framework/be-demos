<?php

declare(strict_types=1);

namespace Be\Demo\Hello\Tests;

use Be\Demo\Hello\Final\Hello;
use Be\Demo\Hello\Input\HelloInput;
use Be\Demo\Hello\Module\AppModule;
use Be\Framework\Becoming;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class HelloTest extends TestCase
{
    private Becoming $becoming;

    protected function setUp(): void
    {
        $injector = new Injector(new AppModule());
        $this->becoming = $injector->getInstance(Becoming::class);
    }

    public function testHelloInputBecomesHello(): void
    {
        $input = new HelloInput(name: 'World');

        /** @var Hello $final */
        $final = ($this->becoming)($input);

        $this->assertInstanceOf(Hello::class, $final);
        $this->assertSame('Hello World', $final->greeting);
    }

    public function testHelloWithDifferentName(): void
    {
        $input = new HelloInput(name: 'Be Framework');

        /** @var Hello $final */
        $final = ($this->becoming)($input);

        $this->assertSame('Hello Be Framework', $final->greeting);
    }
}
