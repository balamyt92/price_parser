<?php

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

require_once 'vendor/autoload.php';

$logger = new Logger('import');
$logger->pushHandler(new RotatingFileHandler('/log/messages.log', 5));

$schema = new \APP\CommerceML2\Schemas\PriceSchema($logger, null, __DIR__);
$parser = new \APP\CommerceML2\Parser('price.xml', $schema);
$parser->parse();
