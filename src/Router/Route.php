<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch\Router;

use Phinch\Middleware\AbstractMiddleware;

use function \uniqid;
use Ds\Stack;
use \Phalcon\Mvc\Router\Route as BaseRoute;
use \Phalcon\Mvc\Router\RouteInterface;

/**
 * Reimplementation of \Phalcon\Mvc\Router\Route.
 *
 * Reimplementation of `\Phalcon\Mvc\Router\Route` for PSR-15
 * compliant per-route middleware.
 */
class Route extends BaseRoute implements RouteInterface
{
  /** @var Stack */
  protected $middlewares;

  /**
   * Class constructor
   *
   * Note: $this->routeId is set via PHP's unique identifier
   * function instead of the process used by Phalcon proper.
   */
  public function __construct(
    string $pattern,
    array $paths = null,
    array $httpMethods = null
  )
  {

    $this->reConfigure($pattern, $paths);
    $this->via($httpMethods);

    $this->routeId = uniqid();

    $this->middlewares = new Stack();
  }

  public function addMiddleware(callable $middleware): self
  {
    return $this;
  }
  /**
   * Reconfigure the route, adding a new pattern and a set
   * of paths.
   *
   * @param string $pattern
   * @param array $paths
   * @return void
   */
  public function reConfigure(string $pattern, array $paths = null): void
  {

  }
}
