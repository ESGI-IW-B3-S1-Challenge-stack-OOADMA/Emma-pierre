<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Initialisation de certaines choses
use App\DependencyInjection\Container;
use App\Routing\RouteNotFoundException;
use App\Routing\Router;
use App\Session\SessionManager;
use App\Utils\Filesystem;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use PDO;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

[
    'DB_HOST'     => $host,
    'DB_PORT'     => $port,
    'DB_NAME'     => $dbname,
    'DB_CHARSET'  => $charset,
    'DB_USER'     => $user,
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

// SessionManager
$sessionManager = new SessionManager();

$serviceContainer = new Container();
$serviceContainer
    ->set(Environment::class, $twig)
    ->set(PDO::class, $pdo)
    ->set(SessionManager::class, $sessionManager);

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

try {
  $router->execute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (RouteNotFoundException $ex) {
  http_response_code(404);
  echo "Page not found";
}
