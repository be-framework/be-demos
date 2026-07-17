<?php

declare(strict_types=1);

namespace Be\Pattern\Hello\Tests;

use Be\Pattern\Hello\Exception\InvalidNameException;
use Be\Pattern\Hello\Final\Hello;
use Be\Pattern\Hello\Input\HelloInput;
use Be\Pattern\Hello\Module\AppModule;
use Be\Pattern\Hello\Semantic\Name;
use Be\Framework\Becoming;
use Be\Framework\Exception\SemanticVariableException;
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

    public function testBecomingFlowRejectsEmptyName(): void
    {
        $this->expectException(SemanticVariableException::class);

        ($this->becoming)(new HelloInput(name: '   '));
    }

    public function testValidName(): void
    {
        $semantic = new Name();
        $semantic->validate('World');
        $this->addToAssertionCount(1);
    }

    public function testEmptyNameThrowsException(): void
    {
        $this->expectException(InvalidNameException::class);
        $semantic = new Name();
        $semantic->validate('');
    }
}
