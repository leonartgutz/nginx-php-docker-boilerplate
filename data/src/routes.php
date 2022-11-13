<?php

use Leonartgutz\NginxPhpDocker\Modules\Example\Connector\Connector as ExampleConnector;
use Leonartgutz\NginxPhpDocker\Tools\Fetcher;
use Leonartgutz\NginxPhpDocker\Tools\Router;

use function Leonartgutz\NginxPhpDocker\Database\create_tables;

try {
  $router = new Router();

  switch ($router->first_level_path()) {
    case '/':
      $router->get();
      $fetch = new Fetcher('http://10.0.0.115:3000');
      $comments = $fetch->post([
        "userId" => 10,
        "title" => "hello the title",
        "body" => "hello the body"
      ]);

      echo $comments;
      break;

    case '/example':
      new ExampleConnector($router, '/example');
      break;

    case '/set-database':
      create_tables();
      echo 'Database created';
      break;

    default:
      throw new \Exception('Not found', 404);
      break;
  }

} catch (Exception $e) {
  switch ($e->getCode()) {
    case 401:
      $router->unauthorized($e->getMessage());
      break;

    case 404:
      $router->not_found($e->getMessage());
      break;

    default:
      $router->bad_request($e->getMessage());
      break;
  }
}
