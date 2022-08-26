<?php
declare(strict_types=1);

namespace App\Kernel\Controller;

use Symfony\Component\Yaml\Yaml;
use App\Filesystem\Folder;
use App\Exceptions\CannotReadFileFromFilesource;

final class DataFunctions
{
  private $conf;

  /**
   * Constructor
   */
  public function __construct()
  {
    // @filepath
    $filepath = Folder::getConfigPath().'/functions.yml';

    // @validate
    if (!is_file($filepath))
      throw new CannotReadFileFromFilesource('Cannot read controller functions configuration file due to insufficient permissions');

    // @conf
		$this->conf = Yaml::parseFile($filepath);
  }

  /**
   * Exists
   *
   * @param string $func
   * @return boolean
   */
  public function has(string $func = ''): bool
  {
    return isset($this->conf[$func]);
  }

  /**
   * Return function value
   *
   * @param string $func
   * @return mixed
   */
  public function get(string $func = ''): mixed
  {
    return (new $this->conf[$func]())->get();
  }
}