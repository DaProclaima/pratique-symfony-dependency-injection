<?php

use App\Controller\OrderController;
use App\Database\Database;
use App\Mailer\GmailMailer;
use App\Mailer\SmtpMailer;
use App\Texter\FaxTexter;
use App\Texter\SmsTexter;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

$container = new \Symfony\Component\DependencyInjection\ContainerBuilder();
$container->autowire('logger', \App\Logger::class);
//$container->register('database', Database::class)
//    ->setAutowired(true);
$container->autowire('database', Database::class);

//$container->register('texter.sms', SmsTexter::class)
//    ->setAutowired(true)
////    those are not services but strings, so this methods needs to remain
//    ->setArguments(["service.sms.com", "apikey123"]);
$container->autowire('texter.sms', SmsTexter::class)->addMethodCall('setLogger', [
    new \Symfony\Component\DependencyInjection\Reference('logger')
])
    ->setArguments(["service.sms.com", "apikey123"]);

//$container->register('mailer.gmail', GmailMailer::class)
//    ->setAutowired(true)
//    ->setArguments(["%mailer.gmail_user%", "%password%"]);

$container->autowire('mailer.gmail', GmailMailer::class)->addMethodCall('setLogger', [
    new \Symfony\Component\DependencyInjection\Reference('logger')
])
    ->setArguments(["%mailer.gmail_user%", "%password%"]);
$container->setParameter('mailer.gmail_user', "snitpro@gmail.com");
$container->setParameter('password', 'password');

//$container->register('mailer.smtp', SmtpMailer::class)
//    ->setAutowired(true)
//    ->setArguments(['smtp:localhost', 'root', '123']);
$container->autowire('mailer.smtp', SmtpMailer::class)
    ->setArguments(['smtp:localhost', 'root', '123']);

//$container->register('texter.fax', FaxTexter::class)
//    ->setAutowired(true);
$container->autowire('texter.fax', FaxTexter::class);

$container->setAlias('App\Controller\OrderController', 'order_controller')->setPublic(true);
$container->setAlias('App\Database\Database', 'database');
$container->setAlias('App\Mailer\GmailMailer', 'mailer.gmail');
$container->setAlias('App\Mailer\SmtpMailer', 'mailer.smtp');
$container->setAlias('App\Mailer\MailerInterface', 'mailer.gmail');
$container->setAlias('App\Texter\SmsTexter', 'texter.sms');
$container->setAlias('App\Texter\FaxTexter', 'texter.fax');
$container->setAlias('App\Texter\TexterInterface', 'texter.sms');

$container->register('order_controller', OrderController::class)
    ->setPublic(true)
//    ->setArguments([
//        new \Symfony\Component\DependencyInjection\Reference(Database::class),
//        new \Symfony\Component\DependencyInjection\Reference(GmailMailer::class),
//        new \Symfony\Component\DependencyInjection\Reference(SmsTexter::class)
//    ])
//        once it is compiled, the container knows the arguments a service needs to be built, provided by alias or service name.
//        + grant to add other dependency injections in construct method without requiring us to add it in setArguments
    ->setAutowired(true)
    ->addMethodCall('sayHello', [
        'Bonjour à tous',
        33
    ])->addMethodCall('setSecondaryMailer', [
        new \Symfony\Component\DependencyInjection\Reference(GmailMailer::class)
    ]);

//Useful:
// To optimise definitions of services
// Detect potential configuration errors  (like circular references: i ask building a db service requiring controller,
// and building a controller requiring a db...)
// To modify definitions in last minute having by hand the whole config
$container->compile();


$controller = $container->get(OrderController::class);
//error as it is private. but above, the Controller ALIAS is set public and is already built-in with the other services we indirectly embed.
//$database = $container->get(Database::class);
// to call the service, it has to be declared public as well
$controller = $container->get('order_controller');

$httpMethod = $_SERVER['REQUEST_METHOD'];

if($httpMethod === 'POST') {
    $controller->placeOrder();
    return;
}

include __DIR__. '/views/form.html.php';
