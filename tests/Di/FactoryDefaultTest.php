<?php declare(strict_typing=1);

namespace Phinch\Test\Di;

use \PHPUnit\Framework\TestCase;

use \Phinch\Di\FactoryDefault;

class FactoryDefaultTest extends TestCase
{
  public function testCanBeInstantiated(): void
  {
    $this->assertInstanceOf(
      FactoryDefault::class,
      new FactoryDefault(),
      'FactoryDefault class could not be instantiated'
    );
  }
}
