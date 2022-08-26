<?php
declare(strict_types=1);

namespace App\Router\Entity;

use App\Router\Entity\Entity;

class Welcome extends Entity
{
  public function __construct()
  {
    parent::__construct(Welcome::class);
  }
}