<?php

namespace Leonartgutz\NginxPhpDocker\Global\Contracts;

interface IModel {
  function create(object $data): object;
  function show(object $data): object;
  function show_many(object $data): object;
  function update(object $data): object;
  function delete(object $data): object;
}
