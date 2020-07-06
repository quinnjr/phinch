<?php declare(strict_types=1);

// (C) 2020 Joseph R. Quinn
// SPDX-License-Identifier: ISC

namespace Phinch\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use \Phalcon\Http\Response;

abstract class AbstractMiddleware
{
  /**
   * Middlware invoke function.
   *
   * Middleware invoke function. Each middleware
   * must define an invocable function to process the
   * server request and/or response.
   *
   * @param ServerRequestInterface $request
   * @param RequestHandler $handler
   *
   * @return Response
   */
  abstract public function __invoke(
    Request $request,
    RequestHandler $handler
  ): Response;
}
