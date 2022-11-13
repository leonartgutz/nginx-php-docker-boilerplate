<?php

namespace Leonartgutz\NginxPhpDocker\Global\Contracts;

interface IConnector {
  function create(): void;
  function show(): void;
  function show_many(): void;
  function update(): void;
  function delete(): void;
}
