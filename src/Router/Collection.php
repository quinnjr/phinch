<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch\Router;

use function \array_push;

use \Ds\Map;
use \Ds\Stack;
use \Phalcon\Mvc\Micro\CollectionInterface;

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
class Collection implements CollectionInterface
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
  public function __constructor()
  {
    $this->handlers = new Map();
    $this->middlewares = new Stack();
    $this->isLazy = false;
  }

  /**
   * Add a middleware to the Middleware stack.
   *
   * @param AbstractMiddleware $middleware
   * @return self
   */
  public function addMiddleware(AbstractMiddleware $middleware): self
  {
    // array_push($this->middlewares, $middleware);
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
  public function delete(string $routePattern, mixed ...$handlers): self
  {
    $this->addMap("DELETE", routePattern, $handlers);
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
  public function get(string $routePattern, mixed ...$handlers): self
  {
    $this->addMap("GET", $routePattern, $handlers);
    return $this;
  }

  /**
   * Get the main handler for the collection.
   *
   * @return mixed
   */
  public function getHandler(): mixed
  {
    return $this->handler;
  }

  /**
   * Returns the array of handlers on the collection.
   *
   * @return array
   */
  public function getHandlers(): array
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
   * Undocumented function
   *
   * @return self
   */
  public function head(): self
  {
    return $this;
  }

  /**
   * Returns whether the main handler should be lazy loaded.
   *
   * @return boolean
   */
  public function isLazy(): bool
  {
    return $this->lazy;
  }

  public function map(): self
  {
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
    mixed ...$handlers
  ): self
  {
    return $this;
  }

  public function options()
  {
    return $this;
  }

  public function patch()
  {
    return $this;
  }

  public function post()
  {
    return $this;
  }

  public function put()
  {
    return $this;
  }

  /**
   * Sets the main handler of the collection.
   *
   * @param mixed $handler
   * @param boolean $isLazy
   * @return self
   */
  public function setHandler(mixed $handler, bool $isLazy = false): self
  {
    $this->handler = $handler;
    $this->lazy = $lazy;

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
    $this->lazy = lazy;
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
    $this->prefix = prefix;
    return $this;
  }

  /**
   * Internal function to add a handler to the group.
   *
   * Internal function to add a handler to the group using PSR-compliant
   * HTTP stack.
   *
   * @param string $method
   * @param string $routePattern
   * @param mixed $handlers
   * @return void
   */
  protected function addMap(
    string $method,
    string $routePattern,
    mixed ...$handlers
  )
  {

  }
}
