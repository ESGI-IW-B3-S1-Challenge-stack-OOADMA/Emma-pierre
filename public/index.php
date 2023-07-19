<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (
    php_sapi_name() !== 'cli' && // Environnement d'exécution != console
    preg_match('/\.(ico|png|jpg|jpeg|css|js|gif|ttf|svg)$/', $_SERVER['REQUEST_URI'])
) {
    return false;
}

// Initialisation de certaines choses
use App\DependencyInjection\Container;
use App\HttpFoundationWish\Request;
use App\HttpFoundationWish\RequestManager;
use App\Routing\RouteNotFoundException;
use App\Routing\Router;
use App\Session\SessionManager;
use App\Utils\Filesystem;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

[
    'DB_HOST' => $host,
    'DB_PORT' => $port,
    'DB_NAME' => $dbname,
    'DB_CHARSET' => $charset,
    'DB_USER' => $user,
    'DB_PASSWORD' => $password
] = $_ENV;

$dsn = "mysql:dbname=$dbname;host=$host:$port;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $ex) {
    echo "Erreur lors de la connexion à la base de données : " . $ex->getMessage();
    exit;
}

// Twig
$loader = new FilesystemLoader(__DIR__ . '/../templates/');
$twig = new Environment($loader, [
    'debug' => $_ENV['APP_ENV'] === 'dev',
    'cache' => __DIR__ . '/../var/twig/',
]);
$twig->addGlobal('flashes', $_SESSION['flash'] ?? []);
$twig->addExtension(new \Twig\Extension\DebugExtension());

// SessionManager
$sessionManager = new SessionManager();
$request = new Request($_GET, $_POST);

$serviceContainer = new Container();
$serviceContainer
->set(Environment::class, $twig)
->set(PDO::class, $pdo)
->set(SessionManager::class, $sessionManager)
->set(Request::class, $request);

$repoClassnames = Filesystem::getClassNames(__DIR__ . "/../src/Repository/*Repository.php");
foreach ($repoClassnames as $repoClassname) {
    $fqcn = "App\\Repository\\" . $repoClassname;
    $classInfos = new ReflectionClass($fqcn);
    if ($classInfos->isAbstract()) {
        continue;
    }
    $repo = new $fqcn($pdo);
    $serviceContainer->set($repo::class, $repo);
}

// Appeler un routeur pour lui transférer la requête
$router = new Router($serviceContainer);
$router->registerRoutes();

if (php_sapi_name() === 'cli') {
    return;
}

$getRouteExtention = new \Twig\TwigFunction('path', function (string $name, array $params = []) use ($router) {
    $route = $router->getRouteByName($name);

        if (!$route) {
        throw new \Exception("Route $name not found");
        }

        $url = $route['url'];

        foreach ($params as $key => $value) {
        $url = str_replace("{{$key}}", $value, $url);
        }

        return $url;
});
$getCurrentRouteExtention = new \Twig\TwigFunction('getCurrentPath', function () use ($router) {
    return $router->getCurrentPath();
});
$getParamsRouteExtention = new \Twig\TwigFunction('getParamsInCurrentPath', function () use ($router) {
    $params = explode("?", $router->getCurrentPath())[1];
    $params = preg_replace("/page=[0-9]+/", "", $params);
    return $params;
});
$generateRouteWithFilters = new \Twig\TwigFunction('generateRouteWithFilters', function (array $filters = []) use ($router) {

    $params = explode("?", $router->getCurrentPath())[1] ?? "";
    $params = preg_replace("/page=[0-9]+/", "", $params);
    $params = explode("&", $params);
    $paramsArray = [];
    foreach ($params as $key => $value) {
        $paramsArray[explode("=", $value)[0]] = explode("=", $value);
    }
    foreach ($filters as $key => $value) {
        $paramsArray[$key] = [$key, $value];
    }
    $path = explode("?", $router->getCurrentPath())[0];
    $params = implode("&", array_map(function ($param) {
        return implode("=", $param);
    }, $paramsArray));

    if ($params) {
        $path .= "?" . $params;
    }

    return $path;
});

$twig->addFunction($getRouteExtention);
$twig->addFunction($getCurrentRouteExtention);
$twig->addFunction($getParamsRouteExtention);
$twig->addFunction($generateRouteWithFilters);

try {
    $router->execute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (RouteNotFoundException $ex) {
    http_response_code(404);
    echo "Page not found";
}
