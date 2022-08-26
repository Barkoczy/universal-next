<?php
declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Middleware\ContentLengthMiddleware;
use App\Exceptions\RouteNotDefined;
use App\Kernel\Environment;
use App\Kernel\Controller\AppController;
use App\Filesystem\Folder;
use App\Extensions\Guard;
use App\Extensions\Twig\Csrf as TwigCsrf;
use App\Extensions\Twig\BasePath as TwigBasePath;
use App\Extensions\Twig\Whitespace as TwigWhitespace;
use App\Router\Enum\HttpRequestMethod;
use App\Router\Enum\RouteObject;
use App\Router\Router;
use App\Middlewares\PermissionMiddleware;

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
		// @runtime
		$this->runtime = $this->getmicrotime();

		// @enviroment
		if (Environment::hasConfigFile())
			(\Dotenv\Dotenv::createImmutable(dirname(__DIR__)))->load();

		// @container
		$this->container = new Container();

		// @setContainer
		AppFactory::setContainer($this->container);

		// @database
		$this->database();

		// @template
		$this->template();

		// @app
    $this->app = AppFactory::create();

		// @middlewares
		$this->middlewares();

		// @router
		$this->router();
  }

	/*****************************************************************************
	* @Public
	*****************************************************************************/

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

	/*****************************************************************************
	* @Internal
	*****************************************************************************/

	/**
	 * Database
	 *
	 * @return void
	 */
	private function database(): void
	{

	}

	/**
	 * Template
	 *
	 * @return void
	 */
	private function template(): void
	{
		$this->container->set('view', function() {
			// @twig
			$twig = Twig::create([
				'app' => '../resources/views'
			], [
				'cache' => "true" === Environment::var('TWIG_CACHE') ? '../cache/twig' : false
			]);

			// @extensions
			$twig->addExtension(new \Fullpipe\TwigWebpackExtension\WebpackExtension(
				Folder::getStaticPath().'/manifest.json', Folder::getWebPath()
			));
			$twig->addExtension(new \voku\twig\MinifyHtmlExtension((new \voku\helper\HtmlMin()), true));
			$twig->addExtension(new TwigBasePath());
			$twig->addExtension(new TwigWhitespace());

			// @return
			return $twig;
		});
	}

	/**
	 * Middlewares
	 *
	 * @return void
	 */
	private function middlewares(): void
	{
		// Response Factory
		$responseFactory = $this->app->getResponseFactory();

		// Register Middleware On Container
		$this->container->set('csrf', function () use ($responseFactory) {
			$guard = new Guard($responseFactory);
			$guard->setPersistentTokenMode(true);
			return $guard;
		});

		// Twig Csrf
		$this->container->get('view')->addExtension(new TwigCsrf($this->container->get('csrf')));

		// Add Twig-View Middleware
		$this->app->add(TwigMiddleware::createFromContainer($this->app));

		// Register Middleware To Be Executed On All Routes
		$this->app->add('csrf');

		// Register Middleware Parser JSON body
		$this->app->addBodyParsingMiddleware();

		/**
		 * The two modes available are
		 * OutputBufferingMiddleware::APPEND (default mode) - Appends to existing response body
		 * OutputBufferingMiddleware::PREPEND - Creates entirely new response body
		 */
		// $mode = OutputBufferingMiddleware::APPEND;
		// $outputBufferingMiddleware = new OutputBufferingMiddleware($mode);

		// ContentLengthMiddleware
		$contentLengthMiddleware = new ContentLengthMiddleware();
		$this->app->add($contentLengthMiddleware);

		// Add Routing Middleware
		$this->app->addRoutingMiddleware();

		/**
		 * Add Error Handling Middleware
		 *
		 * @param bool $displayErrorDetails -> Should be set to false in production
		 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
		 * @param bool $logErrorDetails -> Display error details in error log
		 * which can be replaced by a callable of your choice.
		 
		* Note: This middleware should be added last. It will not handle any exceptions/errors
		* for middleware added after it.
		*/
		$displayErrorDetails = "true" === Environment::var('DISPLAY_ERROR_DETAILS');
  	$logErrors = "true" === Environment::var('LOG_ERRORS');
    $logErrorDetails = "true" === Environment::var('LOG_ERROR_DETAILS');

		// Error Middleware
		$errorMiddleware = $this->app->addErrorMiddleware(
			$displayErrorDetails, 
			$logErrors, 
			$logErrorDetails
		);

		// Set the Not Found Handler
		$errorMiddleware->setErrorHandler(
			HttpNotFoundException::class,
			function (Request $request, \Throwable $exception, bool $displayErrorDetails) {
				$response = new Response();
				$response->getBody()->write('404 NOT FOUND');

				return $response->withStatus(404);
			});

		// Set the Not Allowed Handler
		$errorMiddleware->setErrorHandler(
			HttpMethodNotAllowedException::class,
			function (Request $request, \Throwable $exception, bool $displayErrorDetails) {
				$response = new Response();
				$response->getBody()->write('405 NOT ALLOWED');

				return $response->withStatus(405);
			});
	}

	/**
	 * Router
	 *
	 * @return void
	 */
	private function router(): void
	{
		// @route
		$route = (new Router())->findByURL();

		// @validate
		if ([] === $route)
			throw new RouteNotDefined('The application could not run because any route not defined');

		// @controller
		$this->container->set(RouteObject::controller, function() use ($route) {
			return new AppController($route);
		});

		// @doGet
		if (HttpRequestMethod::GET === $route[RouteObject::method]){
			$this->doGet($route[RouteObject::url], $route[RouteObject::controller]);
		}

		// @doPost
		if (HttpRequestMethod::POST === $route[RouteObject::method]){
			$this->doPost($route[RouteObject::url], $route[RouteObject::controller]);
		}
	}

	/**
	 * doGet
	 *
	 * @param string $url
	 * @param string $controller
	 * @return void
	 */
	private function doGet(string $url = '', string $controller = ''): void
	{
		$this->app->get($url, $controller)->add(PermissionMiddleware::class);
	}

	/**
	 * doPost
	 *
	 * @param string $url
	 * @param string $controller
	 * @return void
	 */
	private function doPost(string $url = '', string $controller = ''): void
	{
		$this->app->post($url, $controller)->add(PermissionMiddleware::class);
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