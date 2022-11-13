<?php

namespace Leonartgutz\NginxPhpDocker\Modules\Example\Controllers;

use Leonartgutz\NginxPhpDocker\Global\Contracts\IController;
use Leonartgutz\NginxPhpDocker\Modules\Example\Model\Model;
use Leonartgutz\NginxPhpDocker\Modules\Example\Validators\Show as ValidatorsShow;

use function Leonartgutz\NginxPhpDocker\Utils\code_dictionary;

class Show implements IController {
  function handle(object $request): object {
    $is_validadted = new ValidatorsShow($request->params);

    $result = (object) [
      'error' => null,
      'data' => null
    ];

    if (!$is_validadted->validate()) {
      $result->error = (object) [
        'code' => 'ERR001',
        'message' => code_dictionary('ERR001')
      ];

      return $result;
    }

    $model = new Model();
    $result->data = $model->show($request->params);

    return $result;
  }
}
