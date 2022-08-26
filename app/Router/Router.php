<?php
declare(strict_types=1);

namespace App\Router;

use App\Router\Entity\Welcome;

final class Router
{
  /*****************************************************************************
	* @Public
	*****************************************************************************/

  /**
   * Find route by URL
   *
   * @return array
   */
  public function findByURL(): array
  {
    return $this->defaultRoute();
  }

  /*****************************************************************************
	* @Internal
	*****************************************************************************/

  /**
   * @return array
   */
  private function defaultRoute(): array
  {
    return (array)(new Welcome());
  }
}