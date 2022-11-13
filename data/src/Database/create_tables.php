<?php

namespace Leonartgutz\NginxPhpDocker\Database;

use Leonartgutz\NginxPhpDocker\Tools\DbConnector;

function create_tables() {
  $sql = file_get_contents(__DIR__ . '/tables.sql');

  $db = new DbConnector();
  $db->execute($sql);
}
