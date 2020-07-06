<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch\Application;

use function \call_user_func;
use function \count;
use function \is_a;
use function \is_callable;
use function \is_null;
use function \is_object;
use function \is_string;

use \Psr\Http\Server\RequestHandlerInterface;

use \Phalcon\Di\DiInterface;
use \Phalcon\Mvc\Micro\LazyLoader;

use Phinch\Middleware\AbstractMiddleware;
use Phinch\Router;
use Phinch\Router\Collection;

/**
 * Phinch Micro Application
 *
 * Phinch Micro Application. Phinch acts as a Proof-of-Concept
 *  intermediate replacement of \Phalcon\Mvc\Micro until proper
 * supporty for PSR-15 middlewares is completed in the main
 * framework.
 *
 * Phinch looks to be as conformant with the current implementations
 * of the HTTP stack in Phalcon as possible to allow for
 * an eventual drop-in replacement of the Phinch application with
 * Phalcon Micro proper or an upgrade from Phinch to Phalcon MVC.
 *
 * Phinch follows and execution of a request-response cycle by
 * * Running application-level middlewares on the request
 * * Running collection (if mounted) middlewares on the request
 * * Running route (if provided) middelwares on the request
 * * Running the request handler for the specified route
 * * Running the route (if provided) middlewares on the response
 * * Running the collection (if mounted) middlewares on the response
 * * Running the application-level middlewares on the response.
 * * Running any `final` handlers following sending of the respsonse.
 *
 * So far, the biggest break between Phalcon and Phinch is the
 * registering of handlers. Phinch expects each handler on the
 * appropriate register (eg: $app->get) to first indicate the match
 * route pattern and then define variadic functions of callables
 * with a final callable being the route handler proper. All other
 * callables are considered middlewares and are executed in order
 * of definintion.
 *
 * Each middleware is suggested to be one of:
 * * A closure implementing the PSR-15 interface.
 * * A function which can be called using the PSR-15 interface.
 * * A class implementing the PSR-15 interface by defining its
 * * middleware function as the magic method `__invoke`.
 * * A class extending `Phinch\Middleware\AbstractMiddleware`.
 */
class Micro extends AbstractApplication implements HttpApplicationInterface
{

  /** @var array */
  public const VERSION = ["0", "1", "0", "alpha0"];

  /**
   * Constructor
   */
  public function __construct(DiInterface $container = null)
  {
    if (!is_null($container)) {
      $this->setDi($container);
    }
  }

  /**
   * Bind an action to the afterBinding event
   *
   * @param callable $handler
   * @return self
   */
  public function afterBinding(callable $handler): self
  {
    return $this;
  }

  /**
   * Undocumented function
   *
   * @param string $routePattern
   * @param callable ...$handlers
   * @return self
   */
  public function get(string $routePattern, callable ...$handlers): self
  {
    return $this;
  }

  /**
   * Custom handler for the request-response lifecycle.
   *
   * Custom handler for the request-response lifecycle. Implements
   * PSR-15 middleware between the current implementation of RequestInterface
   * and ResponseInterface of the Phalcon application.
   *
   * @see https://github.com/phalcon/cphalcon/blob/master/phalcon/Mvc/Micro.zep#L360
   *
   * @since v0.1.0
   * @param string uri
   * @return mixed
   */
  public function handle(string $uri): mixed
  {
    $response = $this->middlewareDispatcher->handle($request);

    if ($request->isHead()) {
      $response->setContent(null);
    }

    return $response;
  }

  /**
   * Undocumented function
   *
   * @param string $routePattern
   * @param callable ...$handlers
   * @return self
   */
  public function head(string $routePattern, callable ...$handlers): self
  {
    return $this;
  }

  /**
   * {Undocumented function}
   *
   * @param string $routePattern
   * @param callable ...$handlers
   * @return self
   */
  public function map(string $routePattern, callable ...$handlers): self
  {
    return $this;
  }

  /**
   * Custom mounter for Phinch\Router\Collection.
   *
   * Custom mounter for Phinch\Router\Collection. Designed to
   * replace the mounting methodology in the core Phalcon Micro
   * application which does not support PSR-15 middleware.
   *
   * @see https://github.com/phalcon/cphalcon/blob/v4.0.6/phalcon/Mvc/Micro.zep#L859
   *
   * @since v0.1.0
   * @param Collection $collection
   * @return self
   */
  public function mount(Collection $collection): self
  {
    $mainHandler = $collection->getHandler();

    if (is_null($mainHandler)) {
      throw new Exception('Collection requires a main handler');
    }

    $handlers = $collection->getHandlers();

    if (!count($handlers)) {
      throw new Exception('Collection has no handlers to mount');
    }

    if ($collection->isLazy()) {
      $lazyHandler = new LazyLoader(mainHandler);
    } else {
      $lazyHandler = $mainHandler;
    }

    $prefix = $collection->getPrefix();

    /**
     * Add the handlers to the Micro application
     */

    return $this;
  }

  /**
   * Undocumented function
   *
   * @param string $routePattern
   * @param callable ...$handlers
   * @return self
   */
  public function options(string $routePattern, callable ...$handlers): self
  {

  }

  /**
   * Undocumented function
   *
   * @param string $routePattern
   * @param callable $handler
   * @return self
   */
  public function patch(string $routePattern, callable ...$handlers): self
  {

  }

  /**
   * Undocumented function
   *
   * @param string $routePattern
   * @param callable ...$handlers
   * @return self
   */
  public function post(string $routePattern, callable ...$handlers): self
  {

  }

  /**
   * Undocumented function
   *
   * @param string $routePattern
   * @param callable ...$handlers
   * @return self
   */
  public function put(string $routePattern, callable ...$handlers): self
  {

  }

  /**
   * Run the application
   *
   * @param string $uri
   * @return void
   */
  public function run(string $uri = null): void
  {
    if (is_null($uri)) {
      $uri = $_SERVER['REQUEST_URI'];
    }

    $response = $this->handle($uri);

    if (!$response->isSent()) {
      $response->sendHeaders()
        ->send();
    }
  }
}
