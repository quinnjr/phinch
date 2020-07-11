<?php declare(strict_types=1);

namespace Phinch\Test\Application;

use \Closure;

use \Ds\Map;
use \PHPUnit\Framework\TestCase;

use \Phinch\Router\Collection;

class MyMiddleware {
  public function __invoke($request, $handler) {
    return 'This worked';
  }
}

class CollectionTest extends TestCase
{

  protected $collection;

  public function setUp(): void
  {
    $this->collection = new Collection();
    $this->collection->setPrefix('/foo');
    $this->collection->setHandler('MyApp\Controller');
  }

  public function testCanBeInstantiated(): void
  {
    $this->assertInstanceOf(
      Collection::class,
      $this->collection,
      'Collection class could not be instantiated'
    );
  }

  public function testDelete(): void
  {
    $this->collection->delete(
      '/{id}',
      new MyMiddleware(),
      function($req, $handler) {
        return 'test function body';
      },
      'deleteAction'
    );

    $routes = $this->collection->getHandlers();

    $routeMethod = $routes->get('DELETE');

    $this->assertTrue($routeMethod->hasKey('/{id}'));

    $route = $routeMethod->get('/{id}');

    $this->assertCount(3, $route);

    foreach ($route as $handler) {
      if (\is_string($handler)) {
        $this->assertSame('deleteAction', $handler);
      } else {
        $this->assertIsCallable($handler);
      }
    }

    $routes->clear();
    $this->assertCount(0, $routes);
  }

  public function testGet(): void
  {
    $this->collection->get(
      '/',
      new MyMiddleware(),
      function($req, $handler) {
        return 'test function body';
      },
      'getAction'
    );

    $routes = $this->collection->getHandlers();

    $routeMethod = $routes->get('GET');

    $this->assertTrue($routeMethod->hasKey('/'));

    $route = $routeMethod->get('/');

    $this->assertCount(3, $route);

    foreach ($route as $handler) {
      if (\is_string($handler)) {
        $this->assertSame('getAction', $handler);
      } else {
        $this->assertIsCallable($handler);
      }
    }

    $routes->clear();
    $this->assertCount(0, $routes);
  }

  public function testGetHandler(): void
  {
    $handler = $this->collection->getHandler();
    $this->assertIsString($handler);
    $this->assertSame('MyApp\Controller', $handler);
  }

  public function testGetHandlers(): void
  {
    $handlers = $this->collection->getHandlers();
    $this->assertInstanceOf(
      Map::class,
      $handlers
    );
    $this->assertIsNumeric($handlers->count());
  }

  public function testGetPrefix(): void
  {
    $prefix = $this->collection->getPrefix();
    $this->assertIsString($prefix);
    $this->assertSame('/foo', $prefix);
  }

  public function testHead(): void
  {
    $this->collection->head(
      '/',
      new MyMiddleware(),
      function($req, $handler) {
        return 'test function body';
      },
      'headAction'
    );

    $routes = $this->collection->getHandlers();

    $routeMethod = $routes->get('HEAD');

    $this->assertTrue($routeMethod->hasKey('/'));

    $route = $routeMethod->get('/');

    $this->assertCount(3, $route);

    foreach ($route as $handler) {
      if (\is_string($handler)) {
        $this->assertSame('headAction', $handler);
      } else {
        $this->assertIsCallable($handler);
      }
    }

    $routes->clear();
    $this->assertCount(0, $routes);
  }

  public function testIsLazy(): void
  {
    $this->assertFalse($this->collection->isLazy());
  }

  public function testMap(): void
  {
    $this->collection->map(
      '/',
      new MyMiddleware(),
      function($req, $handler) {
        return 'test function body';
      },
      'mapAction'
    );

    $routes = $this->collection->getHandlers();

    $this->assertTrue($routes->hasKey('/'));

    $route = $routes->get('/');

    $this->assertCount(3, $route);

    foreach ($route as $handler) {
      if (\is_string($handler)) {
        $this->assertSame('mapAction', $handler);
      } else {
        $this->assertIsCallable($handler);
      }
    }

    $routes->clear();
    $this->assertCount(0, $routes);
  }

  public function testMapVia(): void
  {
    $methods = [ 'GET', 'POST', 'PUT' ];

    $this->collection->mapVia(
      '/{id}',
      $methods,
      new MyMiddleware(),
      function($req, $handler) {
        return 'test function body';
      },
      'myMappedAction'
    );

    $routes = $this->collection->getHandlers();

    foreach($methods as $method) {
      $routeMethod = $routes->get($method);

      $this->assertTrue($routeMethod->hasKey('/{id}'));

      $route = $routeMethod->get('/{id}');

      $this->assertCount(3, $route);

      foreach ($route as $handler) {
        if (\is_string($handler)) {
          $this->assertSame('myMappedAction', $handler);
        } else {
          $this->assertIsCallable($handler);
        }
      }
    }
  }

  public function testOptions(): void
  {
    $this->collection->options(
      '/',
      new MyMiddleware(),
      function($req, $handler) {
        return 'test function body';
      },
      'optionsAction'
    );

    $routes = $this->collection->getHandlers();

    $routeMethod = $routes->get('OPTIONS');

    $this->assertTrue($routeMethod->hasKey('/'));

    $route = $routeMethod->get('/');

    $this->assertCount(3, $route);

    foreach ($route as $handler) {
      if (\is_string($handler)) {
        $this->assertSame('optionsAction', $handler);
      } else {
        $this->assertIsCallable($handler);
      }
    }

    $routes->clear();
    $this->assertCount(0, $routes);
  }

  public function testPatch(): void
  {
    $this->collection->patch(
      '/{id}',
      new MyMiddleware(),
      function($req, $handler) {
        return 'test function body';
      },
      'patchAction'
    );

    $routes = $this->collection->getHandlers();

    $routeMethod = $routes->get('PATCH');

    $this->assertTrue($routeMethod->hasKey('/{id}'));

    $route = $routeMethod->get('/{id}');

    $this->assertCount(3, $route);

    foreach ($route as $handler) {
      if (\is_string($handler)) {
        $this->assertSame('patchAction', $handler);
      } else {
        $this->assertIsCallable($handler);
      }
    }

    $routes->clear();
    $this->assertCount(0, $routes);
  }

  public function testPost(): void
  {
    $this->collection->post(
      '/',
      new MyMiddleware(),
      function($req, $handler) {
        return 'test function body';
      },
      'postAction'
    );

    $routes = $this->collection->getHandlers();

    $routeMethod = $routes->get('POST');

    $this->assertTrue($routeMethod->hasKey('/'));

    $route = $routeMethod->get('/');

    $this->assertCount(3, $route);

    foreach ($route as $handler) {
      if (\is_string($handler)) {
        $this->assertSame('postAction', $handler);
      } else {
        $this->assertIsCallable($handler);
      }
    }

    $routes->clear();
    $this->assertCount(0, $routes);
  }

  public function testPut(): void
  {
    $this->collection->put(
      '/{id}',
      new MyMiddleware(),
      function($req, $handler) {
        return 'test function body';
      },
      'putAction'
    );

    $routes = $this->collection->getHandlers();

    $routeMethod = $routes->get('PUT');

    $this->assertTrue($routeMethod->hasKey('/{id}'));

    $route = $routeMethod->get('/{id}');

    $this->assertCount(3, $route);

    foreach ($route as $handler) {
      if (\is_string($handler)) {
        $this->assertSame('putAction', $handler);
      } else {
        $this->assertIsCallable($handler);
      }
    }

    $routes->clear();
    $this->assertCount(0, $routes);
  }

  public function testSetHandler(): void
  {
    $this->collection->setHandler('Your\Controller');
    $this->assertSame('Your\Controller', $this->collection->getHandler());

    $this->collection->setHandler(function ($req, $handler) {
      return 'foo bar';
    });
    $this->assertIsCallable($this->collection->getHandler());
  }

  public function testSetLazy(): void
  {
    $this->collection->setLazy(true);
    $this->assertTrue($this->collection->isLazy());
  }

  public function testSetPrefix(): void
  {
    $this->collection->setPrefix('/bar');
    $prefix = $this->collection->getPrefix();
    $this->assertIsString($prefix);
    $this->assertSame('/bar', $prefix);
  }
}
