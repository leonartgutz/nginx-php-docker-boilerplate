<?php

namespace Leonartgutz\NginxPhpDocker\Global\Contracts;

interface IController {
  function handle(object $request): object;
}
