<?php

use App\Controller\OrderController;
use App\Database\Database;
use App\Mailer\GmailMailer;
use App\Texter\SmsTexter;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

$container = new \Symfony\Component\DependencyInjection\ContainerBuilder();

//$controllerDefinition = new \Symfony\Component\DependencyInjection\Definition(OrderController::class, [
//    new \Symfony\Component\DependencyInjection\Reference('database'),
//    new \Symfony\Component\DependencyInjection\Reference('mailer.gmail'),
//    new \Symfony\Component\DependencyInjection\Reference('texter.sms')
//]);
$container->register('order_controller', OrderController::class)
    ->setArguments([
        new \Symfony\Component\DependencyInjection\Reference('database'),
        new \Symfony\Component\DependencyInjection\Reference('mailer.gmail'),
        new \Symfony\Component\DependencyInjection\Reference('texter.sms')
    ])
    ->addMethodCall('sayHello', [
        'Bonjour à tous',
        33
    ])->addMethodCall('setSecondaryMailer', [
        new \Symfony\Component\DependencyInjection\Reference('mailer.gmail')
    ]);
;

//$databaseDefinition = new \Symfony\Component\DependencyInjection\Definition('App\Database\Database');
//$databaseDefinition = new \Symfony\Component\DependencyInjection\Definition(Database::class);

//$container->set('database', new Database());
//$container->setDefinition('database', $databaseDefinition);

//$smsTexterDefinition = new \Symfony\Component\DependencyInjection\Definition(SmsTexter::class);
//$smsTexterDefinition->addArgument("service.sms.com")->addArgument("apikey123");
//$smsTexterDefinition->setArguments([
//    "service.sms.com",
//    "apikey123"
//]);
//$smsTexterDefinition = new \Symfony\Component\DependencyInjection\Definition(SmsTexter::class, [
//    "service.sms.com",
//    "apikey123"
//]);
//$container->setDefinition('texter.sms', $smsTexterDefinition);

//$gmailMailerDefinition =  new \Symfony\Component\DependencyInjection\Definition(GmailMailer::class, [
//    "snitpro@gmail.com",
//    "password"
//]);
//$container->setDefinition('mailer.gmail', $gmailMailerDefinition);
$container->register('mailer.gmail', GmailMailer::class)
    ->setArguments([
        "snitpro@gmail.com",
        "password"
    ]);
$container->register('texter.sms', SmsTexter::class)
    ->setArguments([
        "service.sms.com",
        "apikey123"
    ]);
$container->register('database', Database::class);


//$controllerDefinition = new \Symfony\Component\DependencyInjection\Definition(OrderController::class, [
//    $container->get('database'),
//    $container->get('mailer.gmail'),
//    $container->get('texter.sms')
//]);
//$controllerDefinition->addMethodCall('sayHello', [
//    'Bonjour à tous',
//    33
//])->addMethodCall('setSecondaryMailer', [
//    new \Symfony\Component\DependencyInjection\Reference('mailer.gmail')
//]);
//$container->setDefinition('order_controller', $controllerDefinition);

////$database = new Database();
//$database = $container->get('database');
////$texter = new SmsTexter("service.sms.com", "apikey123");
//$texter = $container->get('texter.sms');
////$mailer = new GmailMailer("lior@gmail.com", "123456");
//$mailer = $container->get('mailer.gmail');
//$controller = new OrderController($database, $mailer, $texter);
$controller =  $container->get('order_controller');

$httpMethod = $_SERVER['REQUEST_METHOD'];

if($httpMethod === 'POST') {
    $controller->placeOrder();
    return;
}

include __DIR__. '/views/form.html.php';
