<?php

namespace Leonartgutz\NginxPhpDocker\Tools;

use PDO;
use PDOStatement;

class DbConnector {
  
  function connect(): PDO {
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASSWORD'];
    $database = $_ENV['DB_DATABASE'];
    $driver = $_ENV['DB_DRIVER'];

    $dsn = "$driver:host=$host;port=$port;dbname=$database";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $user, $password, $options);

    return $pdo;
  }

  function execute(string $query, array $params = []): PDOStatement | bool {
    $pdo = $this->connect();

    $stmt = $pdo->prepare($query);

    foreach ($params as $key => $value) {
      $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();

    return $stmt;
  }

  function insert(string $table, object $data): object {
    $columns = [];
    $values = [];
    $params = [];

    foreach ($data as $key => $value) {
      $columns[] = $key;
      $values[] = ":$key";
      $params[$key] = $value;
    }

    $columns = implode(', ', $columns);
    $values = implode(', ', $values);

    $sql = "INSERT INTO $table ($columns) VALUES ($values) RETURNING *";

    $stmt = $this->execute($sql, $params);

    $return = (object) [
      'created' => $stmt->rowCount() > 0,
      'result' => $stmt->fetch(),
    ];

    return $return;
  }

  function show(string $table, int $id): object {
    $params = [
      'id' => $id,
    ];

    $sql = "SELECT * FROM $table WHERE id = :id";

    $stmt = $this->execute($sql, $params);

    $return = (object) [
      'result' => $stmt->fetch(),
    ];

    return $return;
  }

  function show_many(string $table, object $data, object $args = null): object {
    $params = [];

    $sql = "SELECT * FROM $table";

    $count_sql = "SELECT COUNT(*) FROM $table";

    $filter_query = '';

    $args_query = '';

    $i = 0;

    foreach ($data as $key => $value) {
      if ($i === 0) {
        $filter_query .= " WHERE $key LIKE '%' || :$key || '%'";
      } else {
        $filter_query .= " AND $key LIKE '%' || :$key || '%'";
      }

      $params[$key] = $value;

      $i++;
    }

    if (isset($args->order_by) && isset($args->order)) {
      $args_query .= " ORDER BY $args->order_by $args->order";
    }

    if (isset($args->limit) && isset($args->offset) && (isset($args->pagination) && $args->pagination === 'true')) {
      $args_query .= " LIMIT :limit OFFSET :offset";
      $params['limit'] = $args->limit;
      $params['offset'] = $args->offset;
    }

    $stmt = $this->execute($sql . $filter_query . $args_query, $params);

    unset($params['limit']);
    unset($params['offset']);

    $stmt_count = $this->execute($count_sql . $filter_query, $params);
    $total = $stmt_count->fetchColumn();

    $return = (object) [
      'result' => $stmt->fetchAll(),
      'found' => $stmt->rowCount(),
      'pages' => ceil($total / $args->limit),
      'total' => $total,
    ];

    return $return;
  }

  function update(string $table, int $id, array $data): object {
    $params = [
      'id' => $id,
    ];

    $sql = "UPDATE $table SET";

    $i = 0;

    foreach ($data as $key => $value) {
      if ($i === 0) {
        $sql .= " $key = :$key";
      } else {
        $sql .= ", $key = :$key";
      }

      $params[$key] = $value;
    }

    $sql .= " WHERE id = :id RETURNING *";

    $stmt = $this->execute($sql, $params);

    $return = (object) [
      'updated' => $stmt->rowCount() > 0,
      'result' => $stmt->fetch(),
    ];

    return $return;
  }

  function delete(string $table, int $id): object {
    $params = [
      'id' => $id,
    ];

    $sql = "DELETE FROM $table WHERE id = :id";

    $stmt = $this->execute($sql, $params);

    $return = (object) [
      'deleted' => $stmt->rowCount(),
      'id' => $id,
    ];

    return $return;
  }
}
