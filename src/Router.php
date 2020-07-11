<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch;

use \Ds\Map;
use \Phalcon\Di\DiInterface;
use \Phalcon\Di\AbstractInjectionAware;
use \Phalcon\Events\EventsAwareInterface;
use \Phalcon\Http\RequestInterface;
use \Phalcon\Mvc\RouteInterface;

/**
 * Extended router class for Phinch.
 *
 * Extended router class for Phinch. Since the Phalcon router
 * is currently not implemented with PSR-15 in mind, the Router
 * class was extended and reworked for Phinch.
 *
 * Unless necessary, this router should function the same way
 * as the base Phalcon router.
 */
class Router extends AbstractInjectionAware
  implements RouterInterface, EventsAwareInterface
{

}
