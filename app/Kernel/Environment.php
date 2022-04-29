<?php
declare(strict_types=1);

namespace App\Kernel;

final class Environment
{
  /**
   * Environment variable
   *
   * @param string $key
   * @param string $default
   * @return string
   */
  public static function var(string $key = '', string $default = ''): string
  {
    // @key
    if (isset($_ENV[$key]))
      return $_ENV[$key];

    // @default
    return 0 === strlen($default) ? '' : $default;
  }
}