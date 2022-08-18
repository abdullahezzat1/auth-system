<?php

namespace App\Http;

class Validator
{
  public static function validate(array $keys, array $request_params)
  {
    $invalid_keys = [];
    foreach ($keys as $key => $value) {
      $constraints = $value['constraints'];
      if (!isset($request_params[$key])) {
        if (isset($constraints['required'])) {
          $invalid_keys[] = $key;
          continue;
        } else {
          continue;
        }
      }
      if (isset($constraints['length'])) {
        if (strlen($request_params[$key]) > $constraints['length']) {
          $invalid_keys[] = $key;
          continue;
        }
      }
      if (isset($constraints['min_length'])) {
        if (strlen($request_params[$key]) < $constraints['min_length']) {
          $invalid_keys[] = $key;
          continue;
        }
      }
      if (isset($constraints['alpha'])) {
        $str = preg_replace("/[a-zA-Z]/", "", $request_params[$key]);
        if (strlen($str) > 0) {
          $invalid_keys[] = $key;
          continue;
        }
      }
      if (isset($constraints['email'])) {
        $valid = filter_var($request_params[$key], FILTER_VALIDATE_EMAIL);
        if ($valid === false) {
          $invalid_keys[] = $key;
          continue;
        }
      }
      if (isset($constraints['uppercase_letter'])) {
        $found = preg_match("/[A-Z]/", $request_params[$key]);
        if (!$found) {
          $invalid_keys[] = $key;
          continue;
        }
      }
      if (isset($constraints['lowercase_letter'])) {
        $found = preg_match("/[a-z]/", $request_params[$key]);
        if (!$found) {
          $invalid_keys[] = $key;
          continue;
        }
      }
      if (isset($constraints['special_char'])) {
        $found = preg_match("/[\!\@\#\$\%\^\&\*\(\)]/", $request_params[$key]);
        if (!$found) {
          $invalid_keys[] = $key;
          continue;
        }
      }
      if (isset($constraints['identical_to'])) {
        if ($request_params[$key] !== $request_params[$constraints['identical_to']]) {
          $invalid_keys[] = $key;
          continue;
        }
      }
    }
    if (count($invalid_keys) > 0) {
      return [
        'success' => false,
        'keys' => $invalid_keys
      ];
    } else {
      return ['success' => true];
    }
  }
}
