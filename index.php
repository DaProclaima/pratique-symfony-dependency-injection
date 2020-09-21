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
$container->setParameter('mailer.gmail_user', "snitpro@gmail.com");
$container->setParameter('password', 'password');

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

$container->register('mailer.gmail', GmailMailer::class)
    ->setArguments([
        "%mailer.gmail_user",
        "%password"
    ]);
$container->register('texter.sms', SmsTexter::class)
    ->setArguments([
        "service.sms.com",
        "apikey123"
    ]);
$container->register('database', Database::class);

$controller =  $container->get('order_controller');

$httpMethod = $_SERVER['REQUEST_METHOD'];

if($httpMethod === 'POST') {
    $controller->placeOrder();
    return;
}

include __DIR__. '/views/form.html.php';
