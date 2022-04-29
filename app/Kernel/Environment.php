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
    if (false !== getenv($key))
      return getenv($key);

    // @default
    return 0 === strlen($default) ? '' : $default;
  }
}