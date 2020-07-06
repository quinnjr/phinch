<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch\Application;

interface HttpApplicationInterface {

  /**
   * Maps a route to a callable handler which only responds to
   * HTTP 'GET' requests.
   *
   * @param string $routePattern
   * @param callable $handler
   * @return self
   */
  public function get(string $routePattern, callable ...$handlers): self;

  /**
   * Maps a route to a callable handler which only responds to
   * HTTP 'HEAD' requests.
   *
   * @param string $routePattern
   * @param callable $handler
   * @return self
   */
  public function head(string $routePattern, callable ...$handlers): self;

  /**
   * Maps a route to a callable handler which is not constrained
   * by any HTTP method.
   *
   * @param string $routePattern
   * @param callable $handler
   * @return self
   */
  public function map(string $routePattern, callable ...$handlers): self;

  /**
   * Maps a route to a callable handler which only responds to
   * HTTP 'OPTIONS' requests.
   *
   * @param string $routePattern
   * @param callable $handler
   * @return self
   */
  public function options(string $routePattern, callable ...$handlers): self;

  /**
   * Maps a route to a callable handler which only responds to
   * HTTP 'PATCH' requests.
   *
   * @param string $routePattern
   * @param callable $handler
   * @return self
   */
  public function patch(string $routePattern, callable ...$handlers): self;

  /**
   * Maps a route to a callable handler which only responds to
   * HTTP 'POST' requests.
   *
   * @param string $routePattern
   * @param callable $handler
   * @return self
   */
  public function post(string $routePattern, callable ...$handlers): self;

  /**
   * Maps a route to a callable handler which only responds to
   * HTTP 'PUT' requests.
   *
   * @param string $routePattern
   * @param callable $handler
   * @return self
   */
  public function put(string $routePattern, callable ...$handlers): self;
}
