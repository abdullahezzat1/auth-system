<?php

namespace App\Http\Shared;

use App\Models\User;

class Constraints
{
  public const FIRST_NAME = [
    'required' => 'blah',
    'length' => User::LENGTHS['first_name'],
    'min_length' => 3,
    'alpha' => 'blah',
  ];

  public const LAST_NAME = [
    'required' => 'blah',
    'length' => User::LENGTHS['last_name'],
    'min_length' => 3,
    'alpha' => 'blah',
  ];

  public const EMAIL  = [
    'required' => 'blah',
    'length' => User::LENGTHS['email'],
    'email' => 'blah',
  ];

  public const PASSWORD = [
    'required' => 'blah',
    'length' => 64,
    'min_length' => 8,
    'uppercase_letter' => 'blah',
    'lowercase_letter' => 'blah',
    'special_char' => 'blah',
  ];
}
