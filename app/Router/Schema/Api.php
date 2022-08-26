<?php
declare(strict_types=1);

namespace App\Router\Schema;

use App\Router\Enum\RouteObject;
use App\Router\Schema\Schema;

final class Api extends Schema
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
    if (isset($route[RouteObject::template])) {
      $this->template = '@app/'.$route[RouteObject::template];
    } else {
      $this->template = '';
    }
  }
}