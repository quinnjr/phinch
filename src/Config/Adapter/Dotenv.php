<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch\Config;

use function \is_null;
use function \is_readable;

use \Phalcon\Config\Exception;
use \Phalcon\Config;

/**
 * Dotenv Config Parser
 *
 * An experimental Dotenv config parser using Phalcon-only components,
 * thus removing the need for external dependencies. Designed
 * with the intention of being added to the Phalcon framework as
 * a whole.
 */
class Dotenv extends Config
{
  /**
   * Class constructor
   *
   * @TODO Add support for an array of dotenv files to seperate production
   *   and development configurations from a base configuration.
   *
   * @param string $filePath
   */
  public function __constructor(string $filePath = null)
  {
    /*
     * Check if the filePath was set to the dotenv file to load.
     * Otherwise, just load configuration variables from the environment.
     */
     if (!is_null($filePath)) {
       if(!is_readable($filePath)) {
         throw Exception('Dotenv file could not be read');
       }

       /** @TODO Continue file loading logic */
     }

     /** @TODO Continue Phalcon-acceptable config merging */

     parent::__construct();
  }
}
