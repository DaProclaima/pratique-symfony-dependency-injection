<?php


namespace App;


use App\Logger;

interface HasLoggerInterface
{
    public function setLogger(Logger $logger);
}