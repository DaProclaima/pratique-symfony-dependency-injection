<?php

namespace App\Texter;

use App\HasLoggerInterface;
use App\Logger;
use App\Mailer\MailerInterface;

class SmsTexter implements TexterInterface, HasLoggerInterface
{
    protected $serviceDsn;
    protected $key;
    protected $logger;  

    public function __construct(string $serviceDsn, string $key, string $firstname)
    {
        var_dump("Works in SmsTexter: $firstname");
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
