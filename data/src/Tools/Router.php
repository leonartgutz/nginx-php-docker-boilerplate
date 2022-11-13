<?php

namespace Leonartgutz\NginxPhpDocker\Tools;

class Router {
  function __construct() {
    $this->path = $_SERVER['REQUEST_URI'];
    $this->method = $_SERVER['REQUEST_METHOD'];
  }

  function path() {
    $path_with_no_params = explode('?', $this->path)[0];

    if ($path_with_no_params === '/') {
      return '/';
    }
    
    return $path_with_no_params;
  }

  function first_level_path() {
    $path = $this->path();

    if ($path === '/') {
      return '/';
    }

    $path = explode('/', $path)[1];

    return "/$path";
  }

  function request(bool $is_multipart = false): object {
    if (!$is_multipart && !empty($_POST)) {
      $this->bad_request('Request must be JSON');
      exit;
    }

    return (object) [
      'params' => (object) $_GET,
      'body' => $is_multipart ? (object) $_POST : json_decode(file_get_contents('php://input')),
      'cookies' => (object) $_COOKIE,
      'headers' => (object) getallheaders(),
    ];
  }

  function post(): void {
    if ($this->method !== 'POST') {
      $this->bad_request('Invalid method');
      exit;
    }
  }

  function get(): void {
    if ($this->method !== 'GET') {
      $this->bad_request('Invalid method');
      exit;
    }
  }

  function ok(object | array $data): void {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($data, JSON_PRETTY_PRINT);
  }

  function bad_request(string $message): void {
    $obj = (object) [
      'error' => true,
      'message' => $message,
    ];

    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode($obj, JSON_PRETTY_PRINT);
  }

  function not_found(): void {
    $obj = (object) [
      'error' => true,
      'message' => 'Not Found',
    ];

    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode($obj, JSON_PRETTY_PRINT);
  }

  function unauthorized(string $message): void {
    $obj = (object) [
      'error' => true,
      'message' => $message,
    ];

    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode($obj, JSON_PRETTY_PRINT);
  }
}
