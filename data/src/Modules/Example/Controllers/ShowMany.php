<?php

namespace Leonartgutz\NginxPhpDocker\Modules\Example\Controllers;

use Leonartgutz\NginxPhpDocker\Global\Contracts\IController;
use Leonartgutz\NginxPhpDocker\Modules\Example\Model\Model;
use Leonartgutz\NginxPhpDocker\Modules\Example\Validators\ShowMany as ValidatorsShowMany;

use function Leonartgutz\NginxPhpDocker\Utils\code_dictionary;

class ShowMany implements IController {
  function handle(object $request): object {
    $is_validadted = new ValidatorsShowMany($request->params);

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
    $result->data = $model->show_many($request->params);

    return $result;
  }
}
