<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

require_once(__DIR__ . '/../../src/Database/create_tables.php');
require_once(__DIR__ . '/../../src/Utils/code_dictionary.php');
