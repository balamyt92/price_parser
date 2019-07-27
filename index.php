<?php

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

require_once 'vendor/autoload.php';

if (count($_FILES) > 0) {
    $file = __DIR__ . '/price1.xml';
    if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
        echo "Файл корректен и был успешно загружен.\n";
    } else {
        var_dump($file, $_FILES);
        echo "Возможная атака с помощью файловой загрузки!\n";
    }

    $logger = new Logger('import');
    $logger->pushHandler(new RotatingFileHandler('/log/messages.log', 5));

    $schema = new \APP\CommerceML2\Schemas\PriceSchema($logger, null, __DIR__);
    $parser = new \APP\CommerceML2\Parser($file, $schema);
    $parser->parse();

    if (file_exists('result.xml')) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="result.xml"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('result.xml'));
        readfile('result.xml');
        unlink('result.xml');
        exit;
    }
} ?>

<form method="post"
      enctype="multipart/form-data"
      action="index.php">
    <input type="hidden"
           name="MAX_FILE_SIZE"
           value="100000000"/>
    <input name="file"
           type="file">
    <input type="submit">
</form>


