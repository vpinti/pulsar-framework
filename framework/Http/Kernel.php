<?php

declare(strict_types=1);

namespace Pulsar\Framework\Http;

use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

class Kernel
{
    public function handle(Request $request): Response
    {
        // Create a dispatcher
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            $routeCollector->addRoute('GET', '/', function() {
                $content = '<h1>Hello World</h1>';
                return new Response($content);
            });
            
            $routeCollector->addRoute('GET', '/posts/{id:\d+}', function($routeParams) {
                $content = "<h1>This is Post {$routeParams['id']}</h1>";
                
                return new Response($content);
            });
        });

        // Dispatch a URI, to obtain the route info
        $routeInfo = $dispatcher->dispatch(
            $request->server['REQUEST_METHOD'],
            $request->server['REQUEST_URI'],
        );

        [$status, $handler, $vars] = $routeInfo;

        // Call the handler, provided by the route info, in order to create a Response
        return $handler($vars);
    }
}