<?php

namespace Leonartgutz\NginxPhpDocker\Utils;

function code_dictionary(string $code): string {
  $obj = (object) [
    'ERR000' => 'Unknown error',
    'ERR001' => 'Invalid request',
  ];

  return $obj->{$code} ?? 'The code does not exist';
}
