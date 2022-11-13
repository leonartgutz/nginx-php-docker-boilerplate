<?php

namespace Leonartgutz\NginxPhpDocker\Modules\Example\Controllers;

use Leonartgutz\NginxPhpDocker\Global\Contracts\IController;
use Leonartgutz\NginxPhpDocker\Modules\Example\Model\Model;
use Leonartgutz\NginxPhpDocker\Modules\Example\Validators\Update as ValidatorsUpdate;

use function Leonartgutz\NginxPhpDocker\Utils\code_dictionary;

class Update implements IController {
  function handle(object $request): object {
    $is_validadted = new ValidatorsUpdate($request->body);

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
    $result->data = $model->update($request->body);

    return $result;
  }
}
