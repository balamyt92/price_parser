<?php

namespace APP\CommerceML2\Loaders;

use APP\CommerceML2\DTO\DtoInterface;
use APP\CommerceML2\StorageInterface;
use Psr\Log\LoggerInterface;

interface LoaderInterface
{
    public function __construct(
        $timestamp,
        LoggerInterface $logger,
        StorageInterface $storage = null,
        $workDir = null
    );

    public function loadData(DtoInterface $item);

    public function beforeLoad();

    public function afterLoad();

    public function disableAllOldItems($timestamp);
}
