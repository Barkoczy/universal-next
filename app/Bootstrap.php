<?php
declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use Slim\Factory\AppFactory;

final class Bootstrap
{
  private static $_instance = null;
	protected $container;
  protected $app;
	protected $runtime;
	protected $endtime;

  /**
   * Constructor
   */
  private function __construct()
  {
		// @Runtime
		$this->runtime = $this->getmicrotime();

		// @Dotenv
		(\Dotenv\Dotenv::createImmutable(dirname(__DIR__)))->load();

		// @Container
		$this->container = new Container();

		// @setContainer
		AppFactory::setContainer($this->container);

		/**
		 * Instantiate App
		 *
		 * In order for the factory to work you need to ensure you have installed
		 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
		 * ServerRequest creator (included with Slim PSR-7)
		 */
    $this->app = AppFactory::create();

		// @hello-world
    $this->app->get('/', function (Request $request, Response $response, $args) {
      $response->getBody()->write("Hello world!");
      return $response;
  	});
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

		// @self
		return $this;
	}

	/**
	 * Metrics
	 *
	 * @return void
	 */
	public function metrics(): void
	{
		// @Endtime
		$this->endtime = $this->getmicrotime();

		// @Print
		printf("Load: % .2f ms", ($this->endtime - $this->runtime) * 1000);
	}

	/**
	 * Calculate runtime 
	 *
	 * @return float
	 */
	private function getmicrotime(): float
	{
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}
}