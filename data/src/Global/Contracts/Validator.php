<?php

namespace Leonartgutz\NginxPhpDocker\Global\Contracts;

class Validator implements IValidator {
  function __construct(object $data) {
    $this->data = $data;
  }

  function validate(): bool {
    return true;
  }
}
