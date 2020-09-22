<?php

use App\Controller\OrderController;
use App\Controller\TestController;
use App\DependencyInjection\LoggerCompilerPass;
use App\HasLoggerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

$container = new ContainerBuilder();

$container->registerForAutoconfiguration(HasLoggerInterface::class)->addTag('with_logger');

//$loader = new PhpFileLoader($container, new FileLocator([__DIR__ . '/config']));
//$loader->load('services.php');
$loader = new YamlFileLoader($container, new FileLocator([__DIR__ . '/config']));
$loader->load('services.yaml');
$loader->load('services2.yaml');

$container->addCompilerPass(new LoggerCompilerPass());

$container->compile();

$testController = $container->get(TestController::class);
die();
$controller = $container->get(OrderController::class);

$httpMethod = $_SERVER['REQUEST_METHOD'];

if($httpMethod === 'POST') {
    $controller->placeOrder();
    return;
}

include __DIR__. '/views/form.html.php';
