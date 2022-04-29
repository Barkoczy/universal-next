<?php
declare(strict_types=1);

namespace App\Router\Schema;

use App\Router\Enum\RouteObject;
use App\Router\Schema\Schema;

final class Console extends Schema
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
      $this->template = '@console/'.$route[RouteObject::template];
    } else {
      $this->template = '';
    }
  }
}