<?php

use App\Controller\OrderController;
use App\DependencyInjection\LoggerCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

$container = new ContainerBuilder();

$loader = new PhpFileLoader($container, new FileLocator([__DIR__ . '/config']));
$loader->load('services.php');

$container->addCompilerPass(new LoggerCompilerPass());

$container->compile();

$controller = $container->get(OrderController::class);

$httpMethod = $_SERVER['REQUEST_METHOD'];

if($httpMethod === 'POST') {
    $controller->placeOrder();
    return;
}

include __DIR__. '/views/form.html.php';
