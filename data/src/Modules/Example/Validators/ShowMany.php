<?php

namespace Leonartgutz\NginxPhpDocker\Modules\Example\Validators;

use Leonartgutz\NginxPhpDocker\Global\Contracts\Validator;
use Leonartgutz\NginxPhpDocker\Tools\VOS;

class ShowMany extends Validator {
  function __construct(object $data) {
    parent::__construct($data);
  }

  function validate(): bool {
    $shape = (object) [
      'name' => VOS::string((object) [
        'required' => false,
      ]),
      'limit' => VOS::string((object) [
        'required' => false,
      ]),
      'offset' => VOS::string((object) [
        'required' => false,
      ]),
      'order_by' => VOS::string((object) [
        'required' => false,
      ]),
      'order' => VOS::string((object) [
        'required' => false,
      ]),
      'pagination' => VOS::string((object) [
        'required' => false,
      ]),
    ];

    $return = VOS::validate($this->data, $shape);

    return $return;
  }
}
