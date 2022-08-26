<?php
declare(strict_types=1);

namespace App\Router\Schema;

use App\Router\Enum\RouteObject;
use App\Router\Schema\Schema;
use App\Exceptions\MissingParameter;

/**
 * @package Page
 */
final class Page extends Schema
{
  /**
   * Constructor
   *
   * @param array $route
   */
  public function __construct(array $route = [])
  {
    parent::__construct($route);
  }
  
  /**
   * Template
   *
   * @param array $route
   * @return void
   */
  public function setTemplate(array $route = []): void
  {
    if (!isset($route[RouteObject::template]))
      throw new MissingParameter('Missing parameter template in routes configuration file');

    $this->template = '@app/'.$route[RouteObject::template];
  }
}