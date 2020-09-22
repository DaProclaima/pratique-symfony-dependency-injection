<?php

use App\Controller\OrderController;
use App\Database\Database;
use App\HasLoggerInterface;
use App\Logger;
use App\Mailer\GmailMailer;
use App\Mailer\SmtpMailer;
use App\Texter\FaxTexter;
use App\Texter\SmsTexter;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $configurator) {
    $parameters = $configurator->parameters();
    $parameters
        ->set('mailer.gmail_user', 'sebastien@gmail.com')
        ->set('mailer.gmail_password', 'password');
    
    $services = $configurator->services();

    $services->defaults()
        ->autowire(true)
        ->autoconfigure(true)
    ;
    $services->instanceof(HasLoggerInterface::class)->tag('with_logger');

    $services
//        ->set('order_controller', OrderController::class)
//        ->public()
//        ->call('sayHello', ['Bonjour à tous', 33])
//        ->call('setSecondaryMailer', [service('mailer.gmail')])
        ->set('logger', Logger::class)

        ->set('database', Database::class)
        ->arg('$firstname' , 'Sebastien')

        ->set('texter.sms', SmsTexter::class)
        ->args(["service.sms.com", "apikey123"])

        ->set('mailer.gmail', GmailMailer::class)
        ->args(["%mailer.gmail_user%", "%mailer.gmail_password%"])

        ->set('mailer.smtp', SmtpMailer::class)
        ->args(['smtp:localhost', 'root', '123'])
        
        ->set('texter.fax', FaxTexter::class)
        
        
        ->alias('App\Controller\OrderController', 'order_controller')->public()
        ->alias('App\Database\Database', 'database')
        ->alias('App\Mailer\GmailMailer', 'mailer.gmail')
        ->alias('App\Mailer\SmtpMailer', 'mailer.smtp')
        ->alias('App\Mailer\MailerInterface', 'mailer.gmail')
        ->alias('App\Texter\SmsTexter', 'texter.sms')
        ->alias('App\Texter\FaxTexter', 'texter.fax')
        ->alias('App\Texter\TexterInterface', 'texter.sms')
        ->alias('\App\Logger', 'logger')
    ;
};
