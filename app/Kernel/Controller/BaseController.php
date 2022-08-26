<?php
declare(strict_types=1);

namespace App\Kernel\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Kernel\Registry;

class BaseController extends Registry
{
  public function __invoke(Request $request, Response $response, array $args): Response
  {
    // @request
    $this->controller->request($request);

    // @args
    $this->controller->args($args);

    // @response
    return $this->view->render(
      $response, 
      $this->controller->template(), 
      $this->controller->data()
    );
  }
}