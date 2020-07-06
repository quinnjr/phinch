<?php

use Phinch\Application\Micro;
use Phinch\Di\FactoryDefault;
use Phinch\Middleware\AbstractMiddleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Reponse;
use \Psr\Http\Server\RequestHandlerInterface as Handler;

require_once '../../vendor/autoload.php';

class JsonResponseMiddleware extends AbstractMiddleware
{
  public function __invoke(
    Request $request,
    Handler $handler
  ): Response
  {
    $response = $handler->handle($request);
    $content = $response->getRawBody();
    $response->setJsonContent([
      'data' => $content
    ]);
    return $response;
  }
}

$di = new FactoryDefault();

$app = new Micro($di);

$app->get('/hello-world', new JsonResponseMiddleware(), function () {
  return 'Hello World';
});

$app->handle($_SERVER['REQUEST_URI']);
