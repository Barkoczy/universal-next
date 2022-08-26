<?php
declare(strict_types=1);

namespace App\Extensions\Twig;

use App\Extensions\Guard;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class Csrf extends AbstractExtension implements GlobalsInterface
{
  /**
   * @var Guard
   */
  protected $csrf;
  
  /**
   * Constructor
   *
   * @param Guard $csrf
   */
  public function __construct(Guard $csrf)
  {
    $this->csrf = $csrf;
  }

  /**
   * Csrf Global Twig
   *
   * @return array
   */
  public function getGlobals(): array
  {
    return [
      'csrf'   => [
        'keys' => [
          'name'  => $this->csrf->getTokenNameKey(),
          'value' => $this->csrf->getTokenValueKey()
        ],
        'name'  => $this->csrf->getTokenName(),
        'value' => $this->csrf->getTokenValue()
      ]
    ];
  }
}