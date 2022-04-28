<?php
declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

final class Bootstrap
{
  private static $_instance = null;
  protected $app;

  /**
   * Constructor
   */
  private function __construct()
  {
    $this->app = AppFactory::create();
  }

  /**
	 * Singleton
	 *
	 * @return Bootstrap
	 */
	public static function boot(): Bootstrap
	{
		if (self::$_instance === null) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

  /**
	 * Run application
	 *
	 * @return Bootstrap
	 */
	public function run(): Bootstrap
	{
		// @run
		$this->app->run();

    // @hello-world
    $this->app->get('/', function (Request $request, Response $response, $args) {
      $response->getBody()->write("Hello world!");
      return $response;
  	});

		// @self
		return $this;
	}
}