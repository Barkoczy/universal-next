<?php
declare(strict_types=1);

namespace App\Extensions\Twig;

use App\Router\EventHttpRequest;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BasePath extends AbstractExtension
{
  /** @var string **/
  private $basePath;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->basePath = EventHttpRequest::getBaseURL();
  }

  /**
   * @return array
   */
  public function getFunctions(): array
  {
    return [
      new TwigFunction('basePath', function () : string {
        return $this->basePath;
      })
    ];
  }
}