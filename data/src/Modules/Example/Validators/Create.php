<?php

namespace Leonartgutz\NginxPhpDocker\Modules\Example\Validators;

use Leonartgutz\NginxPhpDocker\Global\Contracts\Validator;
use Leonartgutz\NginxPhpDocker\Tools\VOS;

class Create extends Validator {
  function __construct(object $data) {
    parent::__construct($data);
  }

  function validate(): bool {

    $shape = (object) [
      'name' => VOS::string((object) [
        'required' => true,
      ]),
    ];

    $return = VOS::validate($this->data, $shape);

    return $return;
  }
}
