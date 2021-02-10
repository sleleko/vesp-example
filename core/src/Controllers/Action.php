<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Container;
use Fig\Http\Message\RequestMethodInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Throwable;
use Vesp\Controllers\Controller;
use Vesp\Services\Eloquent;

class Action extends Controller
{


    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var ServerRequestInterface $request */
        $routeContext = RouteContext::fromRequest($request);
        $this->route = $routeContext->getRoute();
        $this->request = $request;
        $this->response = $response;

        $name = preg_replace_callback(
            '#-(\w)#',
            static function ($matches) {
                return ucfirst($matches[1]);
            },
            $this->route->getArgument('name')
        );

        $class = '\App\Controllers\\' . implode('\\', array_map('ucfirst', explode('/', $name)));
        $container = new Container();
        $container->set(Eloquent::class, $this->eloquent);

        try {
            /** @var Controller $controller */
            $controller = $container->get($class);
            if ($request->getMethod() === RequestMethodInterface::METHOD_DELETE) {
                $request = $request->withParsedBody($request->getQueryParams());
            }

            return $controller->__invoke($request, $response);
        } catch (Throwable $e) {
            return $this->failure('Not Found', 404);
        }
    }
}
