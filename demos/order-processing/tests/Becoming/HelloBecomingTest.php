<?php

declare(strict_types=1);

namespace Be\App\Tests\Becoming;

use Be\App\Input\HelloInput;
use Be\App\Final\Hello;
use Be\App\Module\AppModule;
use Be\Framework\Becoming;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class HelloBecomingTest extends TestCase
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
}
