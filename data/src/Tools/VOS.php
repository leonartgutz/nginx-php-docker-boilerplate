<?php

namespace Leonartgutz\NginxPhpDocker\Tools;

class VOS {
  static function string(object $options = null): object {
    $allowed_options = ['required', 'min', 'max', 'email', 'url', 'regex'];

    $return = (object) [
      'type' => 'string',
    ];

    foreach ($options as $key => $value) {
      if (!in_array($key, $allowed_options)) {
        throw new \Exception("Invalid option: $key, must be one of: " . implode(', ', $allowed_options));
      }

      $return->$key = $value;
    }

    return $return;
  }

  static function integer(object $options = null): object {
    $allowed_options = ['required', 'min', 'max'];

    $return = (object) [
      'type' => 'integer',
    ];

    foreach ($options as $key => $value) {
      if (!in_array($key, $allowed_options)) {
        throw new \Exception("Invalid option: $key, must be one of: " . implode(', ', $allowed_options));
      }

      $return->$key = $value;
    }

    return $return;
  }

  static function double(object $options = null): object {
    $allowed_options = ['required', 'min', 'max'];

    $return = (object) [
      'type' => 'double',
    ];

    foreach ($options as $key => $value) {
      if (!in_array($key, $allowed_options)) {
        throw new \Exception("Invalid option: $key, must be one of: " . implode(', ', $allowed_options));
      }

      $return->$key = $value;
    }

    return $return;
  }

  static function boolean(object $options = null): object {
    $allowed_options = ['required'];

    $return = (object) [
      'type' => 'boolean',
    ];

    foreach ($options as $key => $value) {
      if (!in_array($key, $allowed_options)) {
        throw new \Exception("Invalid option: $key, must be one of: " . implode(', ', $allowed_options));
      }

      $return->$key = $value;
    }

    return $return;
  }

  static function array(object $options = null): object {
    $allowed_options = ['required', 'min', 'max'];

    $return = (object) [
      'type' => 'array',
    ];

    foreach ($options as $key => $value) {
      if (!in_array($key, $allowed_options)) {
        throw new \Exception("Invalid option: $key, must be one of: " . implode(', ', $allowed_options));
      }

      $return->$key = $value;
    }

    return $return;
  }

  static function object(object $options = null): object {
    $allowed_options = ['required', 'shape'];

    $return = (object) [
      'type' => 'object',
    ];

    foreach ($options as $key => $value) {
      if (!in_array($key, $allowed_options)) {
        throw new \Exception("Invalid option: $key, must be one of: " . implode(', ', $allowed_options));
      }

      $return->$key = $value;
    }

    return $return;
  }

  static function validate(object $data, object $shape): bool {

    foreach ($shape as $key => $value) {
      if (!isset($data->$key)) {
        if (isset($value->required) && $value->required) {
          throw new \Exception("Required field: $key");
        }

        continue;
      }

      if ($value->type !== gettype($data->$key)) {
        throw new \Exception("Invalid type for $key, expects to be $value->type got " . gettype($data->$key), 400);
      }

      if ($value->type !== 'boolean' && isset($value->required) && $value->required && empty($data->$key)) {
        throw new \Exception("Invalid value for $key, it is required", 400);
      }

      if ($value->type === 'string' && isset($value->min) && strlen($data->$key) < $value->min) {
        throw new \Exception("Invalid value for $key, it must be at least $value->min characters", 400);
      }

      if ($value->type === 'string' && isset($value->max) && strlen($data->$key) > $value->max) {
        throw new \Exception("Invalid value for $key, it must be at most $value->max characters", 400);
      }

      if ($value->type === 'string' && isset($value->email) && $value->email && !filter_var($data->$key, FILTER_VALIDATE_EMAIL)) {
        throw new \Exception("Invalid value for $key, it must be a valid email", 400);
      }

      if ($value->type === 'string' && isset($value->url) && $value->url && !filter_var($data->$key, FILTER_VALIDATE_URL)) {
        throw new \Exception("Invalid value for $key, it must be a valid url", 400);
      }

      if ($value->type === 'string' && isset($value->regex) && $value->regex && !preg_match($value->regex, $data->$key)) {
        throw new \Exception("Invalid value for $key, it must match the regex $value->regex", 400);
      }

      if (($value->type === 'integer' || $value->type === 'double') && isset($value->min) && $data->$key < $value->min) {
        throw new \Exception("Invalid value for $key, it must be at least $value->min", 400);
      }

      if (($value->type === 'integer' || $value->type === 'double') && isset($value->max) && $data->$key > $value->max) {
        throw new \Exception("Invalid value for $key, it must be at most $value->max", 400);
      }

      if ($value->type === 'boolean' && isset($value->required) && $value->required && !is_bool($data->$key)) {
        throw new \Exception("Invalid value for $key, it must be a boolean", 400);
      }

      if ($value->type === 'array' && isset($value->min) && count($data->$key) < $value->min) {
        throw new \Exception("Invalid value for $key, it must have at least $value->min items", 400);
      }

      if ($value->type === 'array' && isset($value->max) && count($data->$key) > $value->max) {
        throw new \Exception("Invalid value for $key, it must have at most $value->max items", 400);
      }

      if ($value->type === 'object' && isset($value->required) && $value->required && !is_object($data->$key)) {
        throw new \Exception("Invalid value for $key, it must be an object", 400);
      }

      if ($value->type === 'object' && isset($value->shape) && is_object($data->$key)) {
        self::validate($data->$key, $value->shape);
      }
    }

    return true;
  }
}
