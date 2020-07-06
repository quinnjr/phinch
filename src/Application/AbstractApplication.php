<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch\Application;

use \ArrayAccess;
use \Closure;
use \Throwable;
use function \array_push;
use function \is_null;
use function \is_object;

use \Phalcon\Di\DiInterface;
use \Phalcon\Di\Injectable;
use \Phalcon\Di\ServiceInterface;
use \Phalcon\Events\EventsAwareInterface;
use \Phalcon\Events\ManagerInterface;
use \Phalcon\Http\ResponseInterface;
use \Phalcon\Http\Server\AbstractMiddleware;
use \Phalcon\Mvc\Micro\LazyLoader;
use \Phalcon\Mvc\Model\BinderInterface;
use \Phalcon\Mvc\Router\RouteInterface;

use Phinch\Di\FactoryDefault;
use Phinch\Router;
use Phinch\Router\Collection;

abstract class AbstractApplication extends Injectable
  implements ArrayAccess, EventsAwareInterface
{
  protected $activeHandler;

  /** @var AbstractMiddleware[] */
  protected $afterBindingHandlers = [];

  /** @var AbstractMiddleware[] */
  protected $afterHandlers = [];

  /** @var AbstractMiddleware[] */
  protected $beforeHandlers = [];

  /** @var DiInterface */
  protected $container;

  /** @var Closure */
  protected $errorHandler;

  /** @var EventsManager */
  protected $eventsManager;

  /** @var AbstractMiddleware[] */
  protected $finishHandlers = [];

  /** @var RouteInterface[] */
  protected $handlers = [];

  /** @var BinderInterface */
  protected $modelBinder;

  /** @var RouteInterface */
  protected $notFoundHandler;

  /** @var ResponseInterface */
  protected $responseHandler;

  /** @var mixed */
  protected $returnedValue;

  /** @var  Router */
  protected $router;

  /** @var bool */
  protected $stop;

  /**
   * Undocumented function
   *
   * @param AbstractMiddleware $handler
   * @return self
   */
  abstract public function afterBinding(callable $handler): self;

  /**
   * Sets a handle that will be called when an exception is thrown handling
   * the route.
   *
   * @param Closure handle
   */
  public function error(Closure $handle): self {
    $this->errorHandler = $handler;
    return $this;
  }

  /**
   * Undocumented function
   *
   * @param AbstractMiddleware $handler
   * @return self
   */
  public function finish(AbstractMiddleware $handler): self
  {
    array_push($this->finishHandlers, $handler);
    return $this;
  }

  /**
   * Return the handler that will be called for the
   * matched route.
   *
   * @return callable
   */
  public function getActiveHandler(): callable
  {
    return $this->activeHandler;
  }

  /**
   * Returns the bound models from the binder instance.
   *
   * @return array
   */
  public function getBoundModels(): array
  {
    $modelBinder = $this->modelBinder;

    if (is_null($modelBinder)) {
      return [];
    }

    return $modelBinder->getBoundModels();
  }

  /**
   * Undocumented function
   *
   * @return ManagerInterface
   */
  public function getEventsManager(): ManagerInterface
  {
    return $this->eventsManager;
  }

  /**
   * Undocumented function
   *
   * @param ManagerInterface $ev
   * @return void
   */
  public function setEventsManager(ManagerInterface $ev): void
  {
    $this->eventsManager = $ev;
  }

  /**
   * Undocumented function
   *
   * @return array
   */
  public function getHandlers(): array
  {
    return $this->handlers;
  }

  /**
   * Undocumented function
   *
   * @return mixed
   */
  public function getReturnedValue(): mixed
  {
    return $this->returnedValue;
  }

  /**
   * Returns the internal router used by the
   * application.
   *
   * @return RouterInterface
   */
  public function getRouter(): RouterInterface
  {
    $router = $this->router;

    if(is_object($router) === false) {
      $router = $this->getSharedService('router');

      // Clear the set routes, if any.
      $router->clear();

      // Automatically remove extra slashes.
      $router->removeExtraSlashes(true);

      // Update the internal router.
      $this->router = $router;
    }

    return $router;
  }

  /**
   * Obtains a service from the dependency injector.
   *
   * @return object
   */
  public function getService(string $serviceName): object
  {
    $container = $this->container;

    if(is_object($container) === false) {
      $container = new FactoryDefault();

      $this->container = $container;
    }

    return $container->get($serviceName);
  }

  /**
   * Obtains a shared service from the dependency
   * injector.
   *
   * @return object
   */
  public function getSharedService(string $serviceName): object
  {
    $container = $this->container;

    if(is_object($container) === false) {
      $container = new FactoryDefault();

      $this->container = $container;
    }

    return $container->getShared($serviceName);
  }

  /**
   * Undocumented function
   *
   * @param string $uri
   * @return mixed
   */
  abstract public function handle(string $uri): mixed;

  /**
   * Undocumented function
   *
   * @param string $serviceName
   * @return boolean
   */
  public function hasService(string $serviceName): bool
  {
    $container = $this->container;

    if(is_object($container) === false) {
      $container = new FactoryDefault();

      $this->container = $container;
    }

    return $container->has(serviceName);
  }

  /**
   * Undocumented function
   *
   * @param Collection $collection
   * @return self
   */
  abstract public function mount(Collection $collection): self;

  /**
   * Undocumented function
   *
   * @param callable $handler
   * @return self
   */
  public function notFound(callable $handler): self {
    $this->notFoundHandler = $handler;

    return $this;
  }

  /**
   * Check if a service is registered in the dependency
   * injector using array syntax.
   *
   * @param string alias
   * @return bool
   */
  public function offsetExists($offset)
  {
    return $this->hasService($offset);
  }

  /**
   * Allows for the obtaining of a shared service from
   * the dependency injectorusing array syntax.
   *
   * ```php
   * print_r(
   *   $app['request']
   * );
   * ```
   *
   * @param string offset
   * @return object
   */
  public function offsetGet($offset)
  {
    return $this->getService($offset);
  }

  /**
   * Allows for the registration of a shared service
   * in the dependency injector using array syntax.
   *
   * ```php
   * $app['request'] = new \Phalcon\Http\Request();
   * ```
   *
   * @param string offset
   * @param object definition
   * @return void
   */
  public function offsetSet($offset, $definition)
  {
    $this->setService($offset, $definition);
  }

  /**
   * Removes a service from the dependency injector
   * using array syntax.
   *
   * @param string offset
   * @return void
   */
  public function offsetUnset($offset)
  {
    $container = $this->container;

    if (is_object($container) === false) {
      $container = new FactoryDefault();

      $this->container = $container;
    }

    $container->remove($offset);
  }

  /**
   * Sets an external handler that must be called by the
   * matched route.
   *
   * @param callable $activeHandler
   * @return void
   */
  public function setActiveHandler(callable $activeHandler): void
  {
    $this->activeHandler = $activeHandler;
  }

  /**
   * Sets the dependency injector for the application.
   */
  public function setDI(DiInterface $container): void
  {
    // Automatically register the application as a service.
    if (!$container->has('application')) {
      $container->set('application', this);
    }

    $this->container = $container;
  }

  /**
   * Sets the application model binder.
   *
   * @param BinderInterface modelBinder
   * @param mixed cache
   * @return self
   */
  public function setModelBinder(
    BinderInterface $modelBinder,
    mixed $cache = null
  ): self
  {
    if (\is_string($cache)) {
      $cache = $this->getService($cache);
    }

    if (!is_null($cache)) {
      $modelBinder->setCache($cache);
    }

    $this->modelBinder = $modelBinder;

    return $this;
  }

  /**
   * Appends a custom 'response' handler to be called
   * instead of the default.
   *
   * @param callable handler
   * @return self
   */
  public function setResponseHandler(callable $handler): self {
    $this->responseHandler = $handler;

    return $this;
  }

  /**
   * Stops the execution of middlewares
   *
   * @return void
   */
  public function stop(): void
  {
    $this->stopped = true;
  }
}
