<?php declare(strict_types=1);

namespace Phinch\Test\Application;

use \PHPUnit\Framework\TestCase;

use \Phinch\Exception;

class ExceptionTest extends TestCase
{
  public function testCanBeInstantiated(): void
  {
    $this->assertInstanceOf(
      Exception::class,
      new Exception(),
      'Exception class could not be instantiated'
    );
  }
}
