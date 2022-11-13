<?php

namespace Leonartgutz\NginxPhpDocker\Modules\Example\Connector;

use Leonartgutz\NginxPhpDocker\Global\Contracts\IConnector;
use Leonartgutz\NginxPhpDocker\Modules\Example\Controllers\Create;
use Leonartgutz\NginxPhpDocker\Modules\Example\Controllers\Delete;
use Leonartgutz\NginxPhpDocker\Modules\Example\Controllers\Show;
use Leonartgutz\NginxPhpDocker\Modules\Example\Controllers\ShowMany;
use Leonartgutz\NginxPhpDocker\Modules\Example\Controllers\Update;
use Leonartgutz\NginxPhpDocker\Tools\Router;

class Connector implements IConnector {
  function __construct(Router $router, string $route_prefix) {
    $this->router = $router;
    $this->route_prefix = $route_prefix;

    switch ($this->router->path()) {
      case $this->route_prefix . '/create':
        $this->router->post();
        self::create();
        break;

      case $this->route_prefix . '/show':
        $this->router->get();
        self::show();
        break;

      case $this->route_prefix . '/show-many':
        $this->router->get();
        self::show_many();
        break;

      case $this->route_prefix . '/update':
        $this->router->post();
        self::update();
        break;

      case $this->route_prefix . '/delete':
        $this->router->post();
        self::delete();
        break;

      default:
        $this->router->not_found();
        break;
    }
  }

  function create(): void {
    $controller = new Create();
    $result = $controller->handle($this->router->request());

    if ($result->error) {
      throw new \Exception($result->error->message, 400);
    }

    $this->router->ok($result->data);
  }

  function show(): void {
    $controller = new Show();
    $result = $controller->handle($this->router->request());

    if ($result->error) {
      throw new \Exception($result->error->message, 400);
    }

    $this->router->ok($result->data);
  }

  function show_many(): void {
    $controller = new ShowMany();
    $result = $controller->handle($this->router->request());

    if ($result->error) {
      throw new \Exception($result->error->message, 400);
    }

    $this->router->ok($result->data);
  }

  function update(): void {
    $controller = new Update();
    $result = $controller->handle($this->router->request());

    if ($result->error) {
      throw new \Exception($result->error->message, 400);
    }

    $this->router->ok($result->data);
  }

  function delete(): void {
    $controller = new Delete();
    $result = $controller->handle($this->router->request());

    if ($result->error) {
      throw new \Exception($result->error->message, 400);
    }

    $this->router->ok($result->data);
  }
}
