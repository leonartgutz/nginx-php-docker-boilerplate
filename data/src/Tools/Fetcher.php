<?php

namespace Leonartgutz\NginxPhpDocker\Tools;

class Fetcher {
  function __construct($url) {
    $this->url = $url;
  }

  function get() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }

  function post(array $data, array $args = null) {
    $to_post = json_encode($data);
    $header = ['Content-Type: application/json'];

    foreach ($args as $key => $value) {
      $header[] = $key . ': ' . $value;
    }

    $options = [
      CURLOPT_URL => $this->url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => $to_post,
      CURLOPT_HTTPHEADER => $header
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }
}
