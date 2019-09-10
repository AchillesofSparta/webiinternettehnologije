<?php
    require_once 'vendor/autoload.php';
    require_once 'Configuration.php';

    ob_start();

    use App\Core\DatabaseConfiguration;
    use App\Core\DatabaseConnection;
    use App\Core\Router;
    use App\Core\Route;
    use App\Models\UserModel;
    use App\Models\HallModel;
    use App\Controllers\MainController;
    use App\Core\Model;

    $databaseConfiguration = new DatabaseConfiguration(
        Configuration::DATABASE_HOST,
        Configuration::DATABASE_USER,
        Configuration::DATABASE_PASS,
        Configuration::DATABASE_NAME
    );

    $databaseConnection = new DatabaseConnection($databaseConfiguration);


    $url = strval(filter_input(INPUT_GET, "URL"));
    $httpMethod =filter_input(INPUT_SERVER, "REQUEST_METHOD");

    $router = new Router();
    $routes = require_once "Routes.php";

    foreach ($routes as $route){
        $router->add($route);
    }

    $route = $router->find($httpMethod, $url);
    $arguments = $route->extractArguments($url);
    
    $fullControllerName = "\\App\\Controllers\\" . $route->getControllerName() . "Controller";
    $controller = new $fullControllerName($databaseConnection);

    # Pravljenje fingerprint provajdera
    $fingerprintProviderFactoryClass = Configuration::FINGERPRINT_PROVIDER_FACTORY;
    $fingerprintProviderFactoryMethod = Configuration::FINGERPRINT_PROVIDER_METHOD;
    $fingerprintProviderFactoryArgs = Configuration::FINGERPRINT_PROVIDER_ARGS;
    $fingerprintProviderFactory = new $fingerprintProviderFactoryClass;
    $fingerprintProvider = $fingerprintProviderFactory->$fingerprintProviderFactoryMethod(...$fingerprintProviderFactoryArgs);

    # PRAVLJENJE SESIJE
    $sessionStorageClassName = Configuration::SESSION_STORAGE;
    $sessionStorageConstructorArguments = Configuration::SESSION_STORAGE_DATA;
    $sessionStorage = new $sessionStorageClassName(...$sessionStorageConstructorArguments);

    $session = new App\Core\Session\Session($sessionStorage, Configuration::SESSION_LIFETIME);
    $session->setFingerprintProvider($fingerprintProvider);

    $controller->setSession($session);
    $controller->getSession()->reload();

    # Check for redirect with __pre()
    $controller->__pre();
    call_user_func_array([$controller, $route->getMethodName()], $arguments);
    $controller->getSession()->save();
    
    $data = $controller->getData();

    # API CHECK
    if($controller instanceof \App\Core\ApiController){
        ob_clean();
        header("Content-type: application/json; charset=utf-8");
        #Dozvoli remote access
        #header("Access-Control-Allow-Origin: *");
        echo json_encode($data);
        exit;
    }


    // Template View Rendering
    // Set AUTO_RELOAD to FALSE or REMOVE upon project completion
    $loader = new Twig_Loader_Filesystem("./views");
    $twig = new Twig_Environment($loader, [
        "cache" => "./twig-cache",
        "auto_reload" => true
    ]);

    # Dodaj root direktorijuma
    $data["BASE"] = Configuration::BASE;

    echo $twig->render($route->getControllerName() . "/" . $route->getMethodName() . ".html", $data);
    

    # EOF