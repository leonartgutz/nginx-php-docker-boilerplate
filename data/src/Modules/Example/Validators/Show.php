<?php

namespace Leonartgutz\NginxPhpDocker\Modules\Example\Validators;

use Leonartgutz\NginxPhpDocker\Global\Contracts\Validator;
use Leonartgutz\NginxPhpDocker\Tools\VOS;

class Show extends Validator {
  function __construct(object $data) {
    parent::__construct($data);
  }

  function validate(): bool {
    $shape = (object) [
      'id' => VOS::string((object) [
        'required' => true,
      ]),
    ];

    $return = VOS::validate($this->data, $shape);

    return $return;
  }
}
