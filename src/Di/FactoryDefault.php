<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch\Di;

use \Phalcon\Di as DependencyInjector;
use \Phalcon\Di\Service;

/**
 * Phinch Dependency Injector defaults.
 *
 * Phinch Dependency Injector defaults. Phinch only registers
 * the bare minimum services from the Phalcon framework for the
 * Micro application to function.
 *
 * Additional middlewares from the framework proper, such as
 * `Phalcon\Filter`, must be registered manually in the application
 * during bootstrapping.
 */
class FactoryDefault extends DependencyInjector {

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();

    $this->services = [
      "dispatcher" => new Service("Phalcon\\Mvc\\Dispatcher", true),
      "eventsManager" => new Service("Phalcon\\Events\\Manager", true),
      "modelsManager" => new Service("Phalcon\\Mvc\\Model\\Manager", true),
      "modelsMetadata" => new Service("Phalcon\\Mvc\\Model\\MetaData\\Memory", true),
      "request" => new Service("\\Phalcon\\Http\\Request", true),
      "response" => new Service("\\Phalcon\\Http\\Response", true),
      "router" => new Service("\\Phalcon\\Mvc\\Router", true),
      "transactionManager" => new Service("Phalcon\\Mvc\\Model\\Transaction\\Manager", true)
    ];
  }
}
