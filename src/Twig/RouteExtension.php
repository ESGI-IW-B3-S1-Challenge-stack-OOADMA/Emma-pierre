<?php

use App\Routing\Router;
use Twig\Environment;
use Twig\TwigFunction;

class RouteExtension
{
    public function __construct(
        private Environment $twig,
        private Router $router,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
        new TwigFunction('path', [$this, 'getRoute'])
        ];
    }

    public function getRoute(string $name, array $params = []): string
    {
        $route = $this->router->getRouteByName($name);

        if (!$route) {
        throw new \Exception("Route $name not found");
        }

        $url = $route['url'];

        foreach ($params as $key => $value) {
        $url = str_replace("{{$key}}", $value, $url);
        }

        return $url;
    }
}