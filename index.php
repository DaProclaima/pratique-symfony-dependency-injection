<?php

use App\DependencyInjection\LoggerCompilerPass;
use App\HasLoggerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

$start = microtime(true);
$container = new ContainerBuilder();
if( file_exists(__DIR__ . '/config/container.php')) {
    require_once __DIR__ . '/config/container.php';
    $container = new ProjectServiceContainer();
} else {
    $container->registerForAutoconfiguration(HasLoggerInterface::class)->addTag('with_logger');

    //$loader = new PhpFileLoader($container, new FileLocator([__DIR__ . '/config']));
    //$loader->load('services.php');
    $loader = new YamlFileLoader($container, new FileLocator([__DIR__ . '/config']));
    $loader->load('services.yaml');
    $loader->load('services2.yaml');

    $container->addCompilerPass(new LoggerCompilerPass());

    $container->compile();

    $dumper = new PhpDumper($container);
    file_put_contents(__DIR__ . '/config/container.php', $dumper->dump());
    dump($dumper->dump());

    //$testController = $container->get(TestController::class);
    //die();

}
    $controller = $container->get('order_controller');

    $duration = microtime(true) - $start;

    dump("Build duration =", $duration * 1000);
    // to speed up perfs, we gonna store compiled container in a class ProjectServiceContainer in config/container.php
// Attention ! Any modification made in services.yaml is not considered unless we erase the cached container so that a new one with changes is created

$httpMethod = $_SERVER['REQUEST_METHOD'];

if($httpMethod === 'POST') {
    $controller->placeOrder();
    return;
}

include __DIR__. '/views/form.html.php';
