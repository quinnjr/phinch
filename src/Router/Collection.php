<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch\Router;

use function \is_null;

use \Ds\Map;
use \Ds\Stack;

use Phinch\Exception;
use Phinch\Middleware\AbstractMiddleware;

/**
 * Phinch\Router\Collection
 *
 * Groups one or more HTTP Request-Response handlers as controllers with
 * per-collection and per-route middlewares.
 *
 * ```php
 * $app = new \Phinch\Micro();
 *
 * $collection = new \Phinch\Router\Collection();
 *
 * $collection->setHandler(new MyController());
 *
 * $collection->get("/hello-world", new MyMiddleware(), 'helloWorlAction');
 * ```
 */
class Collection
{
  /** @var bool */
  protected $isLazy;

  /** @var string */
  protected $prefix;

  /** @var callable|string */
  protected $handler;

  /** @var Map */
  protected $handlers;

  /** @var Stack */
  protected $middlewares;

  /**
   * Class constructor
   */
  public function __construct()
  {
    $this->handlers = new Map();
    $this->middlewares = new Stack();
    $this->isLazy = false;
  }

  /**
   * Add a middleware to the Middleware stack.
   *
   * @param callable $middleware
   * @return self
   */
  public function addMiddleware(callable $middleware): self
  {
    $this->middlewares->push($middleware);
    return $this;
  }

  /**
   * Maps a route to a handler that only matches if the HTTP method is DELETE.
   *
   * Maps a route to a handler that only matches if the Http method is DELETE.
   * All handlers added to the $handlers variadic parameter are considered
   * middlewares to be executed during the route handle cycle, with a final
   * string being assumed to be the Controller method to invoke as the route
   * handler proper. If only middlewares or closures are provided, the
   * middlewares and closures compose the route.
   *
   * @param string $routePattern
   * @param callable|string $handlers
   * @return self
   */
  public function delete(string $routePattern, ...$handlers): self
  {
    $this->addMap('DELETE', $routePattern, $handlers);
    return $this;
  }

  /**
   * Maps a route to a handler that only matches if the HTTP method is GET.
   *
   * Maps a route to a handler that only matches if the Http method is GET.
   * All handlers added to the $handlers variadic parameter are considered
   * middlewares to be executed during the route handle cycle, with a final
   * string being assumed to be the Controller method to invoke as the route
   * handler proper. If only middlewares or closures are provided, the
   * middlewares and closures compose the route.
   *
   * @param string $routePattern
   * @param callable|string $handlers
   * @return self
   */
  public function get(string $routePattern, ...$handlers): self
  {
    $this->addMap("GET", $routePattern, $handlers);
    return $this;
  }

  /**
   * Get the main handler for the collection.
   *
   * @return mixed
   */
  public function getHandler()
  {
    return $this->handler;
  }

  /**
   * Returns the map of handlers on the collection.
   *
   * @return Map
   */
  public function getHandlers(): Map
  {
    return $this->handlers;
  }

  /**
   * Returns the collection prefix, if any.
   *
   * @return string
   */
  public function getPrefix(): string
  {
    return $this->prefix;
  }

  /**
   * Maps a route to a handler that only matches if the HTTP method is HEAD.
   *
   * Maps a route to a handler that only matches if the Http method is HEAD.
   * All handlers added to the $handlers variadic parameter are considered
   * middlewares to be executed during the route handle cycle, with a final
   * string being assumed to be the Controller method to invoke as the route
   * handler proper. If only middlewares or closures are provided, the
   * middlewares and closures compose the route.
   *
   * @param string $routePattern
   * @param callable|string $handlers
   * @return self
   */
  public function head(string $routePattern, ...$handlers): self
  {
    $this->addMap("HEAD", $routePattern, $handlers);
    return $this;
  }

  /**
   * Returns whether the main handler should be lazy loaded.
   *
   * @return boolean
   */
  public function isLazy(): bool
  {
    return $this->isLazy;
  }

    /**
   * Maps a route to a handler.
   *
   * Maps a route to a handler.
   * All handlers added to the $handlers variadic parameter are considered
   * middlewares to be executed during the route handle cycle, with a final
   * string being assumed to be the Controller method to invoke as the route
   * handler proper. If only middlewares or closures are provided, the
   * middlewares and closures compose the route.
   *
   * @param string $routePattern
   * @param callable|string $handlers
   * @return self
   */
  public function map(string $routePattern, ...$handlers): self
  {
    $this->addMap(null, $routePattern, $handlers);
    return $this;
  }

  /**
   * Maps a route to a handler via its method(s).
   *
   * Maps a route to a handler via its method(s). Note, this is a BREAKING
   * change from the same function in `Phalcon\Mvc\Micro\Collection`.
   *
   * @see https://github.com/phalcon/cphalcon/blob/master/phalcon/Mvc/Micro/Collection.zep#L137
   *
   * @param string $routePattern
   * @param array $methods
   * @param callable|string $handlers
   * @return self
   */
  public function mapVia(
    string $routePattern,
    array $methods,
    ...$handlers
  ): self
  {
    foreach ($methods as $method) {
      $this->addMap($method, $routePattern, $handlers);
    }

    return $this;
  }

    /**
   * Maps a route to a handler that only matches if the HTTP method is OPTIONS.
   *
   * Maps a route to a handler that only matches if the Http method is OPTIONS.
   * All handlers added to the $handlers variadic parameter are considered
   * middlewares to be executed during the route handle cycle, with a final
   * string being assumed to be the Controller method to invoke as the route
   * handler proper. If only middlewares or closures are provided, the
   * middlewares and closures compose the route.
   *
   * @param string $routePattern
   * @param callable|string $handlers
   * @return self
   */
  public function options(string $routePattern, ...$handlers)
  {
    $this->addMap("OPTIONS", $routePattern, $handlers);
    return $this;
  }

    /**
   * Maps a route to a handler that only matches if the HTTP method is PATCH.
   *
   * Maps a route to a handler that only matches if the Http method is PATCH.
   * All handlers added to the $handlers variadic parameter are considered
   * middlewares to be executed during the route handle cycle, with a final
   * string being assumed to be the Controller method to invoke as the route
   * handler proper. If only middlewares or closures are provided, the
   * middlewares and closures compose the route.
   *
   * @param string $routePattern
   * @param callable|string $handlers
   * @return self
   */
  public function patch(string $routePattern, ...$handlers)
  {
    $this->addMap("PATCH", $routePattern, $handlers);
    return $this;
  }

    /**
   * Maps a route to a handler that only matches if the HTTP method is POST.
   *
   * Maps a route to a handler that only matches if the Http method is POST.
   * All handlers added to the $handlers variadic parameter are considered
   * middlewares to be executed during the route handle cycle, with a final
   * string being assumed to be the Controller method to invoke as the route
   * handler proper. If only middlewares or closures are provided, the
   * middlewares and closures compose the route.
   *
   * @param string $routePattern
   * @param callable|string $handlers
   * @return self
   */
  public function post(string $routePattern, ...$handlers)
  {
    $this->addMap("POST", $routePattern, $handlers);
    return $this;
  }

    /**
   * Maps a route to a handler that only matches if the HTTP method is PUT.
   *
   * Maps a route to a handler that only matches if the Http method is PUT.
   * All handlers added to the $handlers variadic parameter are considered
   * middlewares to be executed during the route handle cycle, with a final
   * string being assumed to be the Controller method to invoke as the route
   * handler proper. If only middlewares or closures are provided, the
   * middlewares and closures compose the route.
   *
   * @param string $routePattern
   * @param callable|string $handlers
   * @return self
   */
  public function put(string $routePattern, ...$handlers)
  {
    $this->addMap("PUT", $routePattern, $handlers);
    return $this;
  }

  /**
   * Sets the main handler of the collection.
   *
   * @param callable|string $handler
   * @param boolean $isLazy
   * @return self
   */
  public function setHandler($handler, bool $isLazy = false): self
  {
    $this->handler = $handler;
    $this->lazy = $isLazy;

    return $this;
  }

  /**
   * Set the main handler to be or not to be lazy loaded
   *
   * @param bool $isLazy
   * @return self
   */
  public function setLazy(bool $isLazy): self
  {
    $this->isLazy = $isLazy;
    return $this;
  }

  /**
   * Sets a prefix for all route added to the collection
   *
   * @param string $prefix
   * @return self
   */
  public function setPrefix(string $prefix): self
  {
    $this->prefix = $prefix;
    return $this;
  }

  /**
   * Internal function to add a handler to the group.
   *
   * Internal function to add a handler to the group using
   * a PSR-15 HTTP stack.
   *
   * @param string|null $method
   * @param string $routePattern
   * @param mixed $handlers
   * @return void
   */
  protected function addMap(
    $method,
    string $routePattern,
    array $handlers
  ): void
  {
    if (is_null($method)) {
      $this->handlers->put($routePattern, $handlers);
    } elseif (!$this->handlers->hasKey($method)) {
      $methodMap = new Map();
      $methodMap->put($routePattern, $handlers);
      $this->handlers->put($method, $methodMap);
    } else {
      $methodMap = $this->handlers->get($method);
      $methodMap->push($routePattern, $handlers);
    }
  }
}
