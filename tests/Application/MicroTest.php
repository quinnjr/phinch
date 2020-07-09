<?php declare(strict_types=1);

namespace Phinch\Test\Application;

use \PHPUnit\Framework\TestCase;

use \Phinch\Application\Micro;

class MicroTest extends TestCase
{
  public function testCanBeInstantiated(): void
  {
    $this->assertInstanceOf(
      Micro::class,
      new Micro(),
      'Micro class could not be instantiated'
    );
  }
}
