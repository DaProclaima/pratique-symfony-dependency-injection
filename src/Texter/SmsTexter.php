<?php

namespace App\Texter;

use App\Logger;
use App\Mailer\MailerInterface;

class SmsTexter implements TexterInterface
{
    protected $serviceDsn;
    protected $key;
    protected $logger;  

    public function __construct(string $serviceDsn, string $key, MailerInterface $mailer)
    {
        var_dump('Works in SmsTexter: ' , $mailer);
        $this->serviceDsn = $serviceDsn;
        $this->key = $key;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
        $this->logger->log('Works in SMS');
    }

    public function send(Text $text)
    {
        var_dump("ENVOI DE SMS : ", $text);
    }
}
