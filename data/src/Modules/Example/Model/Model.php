<?php

namespace Leonartgutz\NginxPhpDocker\Modules\Example\Model;

use Leonartgutz\NginxPhpDocker\Tools\DbConnector;
use Leonartgutz\NginxPhpDocker\Global\Contracts\IModel;

class Model implements IModel {
  private string $table = 'example';

  function __construct() {
    $this->db = new DbConnector();
  }

  function create(object $data): object {
    return $this->db->insert($this->table, $data);
  }

  function show(object $data): object {
    return $this->db->show($this->table, $data->id);
  }

  function show_many(object $data): object {
    $args = (object) [
      'limit' => $data->limit ?? 10,
      'offset' => $data->offset ?? 0,
      'order_by' => $data->order_by ?? 'id',
      'order' => $data->order ?? 'ASC',
      'pagination' => $data->pagination ?? 'true',
    ];

    unset($data->limit);
    unset($data->offset);
    unset($data->order_by);
    unset($data->order);
    unset($data->pagination);

    return $this->db->show_many($this->table, $data, $args);
  }

  function update(object $data): object {
    $id = $data->id;
    unset($data->id);

    return $this->db->update($this->table, $id, (array) $data);
  }

  function delete(object $data): object {
    return $this->db->delete($this->table, $data->id);
  }
}
