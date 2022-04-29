<?php
declare(strict_types=1);

namespace App\Router\Schema;

abstract class Schema
{
  public $method;
  public $url;
  public $controller;
  public $template;
  public $data;

  /**
   * Constructor
   *
   * @param array $route
   */
  public function __construct(array $route = [])
  {
  }
}