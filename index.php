<?php

use App\Controller\OrderController;
use App\Database\Database;
use App\DependencyInjection\LoggerCompilerPass;
use App\Logger;
use App\Mailer\GmailMailer;
use App\Mailer\SmtpMailer;
use App\Texter\FaxTexter;
use App\Texter\SmsTexter;
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

//$container->autowire('logger', Logger::class);

//$container->autowire('database', Database::class);

//$container->autowire('texter.sms', SmsTexter::class)
//    ->setArguments(["service.sms.com", "apikey123"])
//    ->addTag('with_logger');

//$container->autowire('mailer.gmail', GmailMailer::class)
//    ->setArguments(["%mailer.gmail_user%", "%password%"])
//    ->addTag('with_logger');
//$container->setParameter('mailer.gmail_user', "snitpro@gmail.com");
//$container->setParameter('password', 'password');

//$container->autowire('mailer.smtp', SmtpMailer::class)
//    ->setArguments(['smtp:localhost', 'root', '123']);
//
//$container->autowire('texter.fax', FaxTexter::class);

//$container->setAlias('App\Controller\OrderController', 'order_controller')->setPublic(true);
//$container->setAlias('App\Database\Database', 'database');
//$container->setAlias('App\Mailer\GmailMailer', 'mailer.gmail');
//$container->setAlias('App\Mailer\SmtpMailer', 'mailer.smtp');
//$container->setAlias('App\Mailer\MailerInterface', 'mailer.gmail');
//$container->setAlias('App\Texter\SmsTexter', 'texter.sms');
//$container->setAlias('App\Texter\FaxTexter', 'texter.fax');
//$container->setAlias('App\Texter\TexterInterface', 'texter.sms');
//$container->setAlias('\App\Logger', 'logger');

$container->addCompilerPass(new LoggerCompilerPass());

$container->compile();


$controller = $container->get(OrderController::class);

$httpMethod = $_SERVER['REQUEST_METHOD'];

if($httpMethod === 'POST') {
    $controller->placeOrder();
    return;
}

include __DIR__. '/views/form.html.php';
