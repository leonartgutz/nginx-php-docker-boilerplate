<?php

namespace Leonartgutz\NginxPhpDocker\Global\Contracts;

interface IValidator {
  function validate(): bool;
}
