<?php


namespace App\Controller;


use App\Database\Database;
use App\Database\MongoDb;
use App\Mailer\MailerInterface;

class TestController
{
//    public function __construct(Database $database, MailerInterface $mailer)
//    {
//        dump($database, $mailer);
//    }
     public function __construct(MongoDb $database, MailerInterface $mailer)
        {
            dump($database, $mailer);
        }

}