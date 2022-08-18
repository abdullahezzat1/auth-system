<?php

namespace App\Http;

class Helpers
{
  public static function password_hash_with_pepper($password)
  {
    $pepper = env('PASSWORD_PEPPER');
    $peppered_password = hash_hmac("sha256", $password, $pepper);
    $hashed = password_hash($peppered_password, PASSWORD_BCRYPT);
    return $hashed;
  }

  public static function password_verify_with_pepper($user_password, $db_password)
  {
    $pepper = env('PASSWORD_PEPPER');
    $peppered_password = hash_hmac("sha256", $user_password, $pepper);
    return password_verify($peppered_password, $db_password);
  }
}
