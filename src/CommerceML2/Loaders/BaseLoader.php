<?php

namespace APP\CommerceML2\Loaders;

use APP\CommerceML2\DTO\DtoInterface;
use APP\CommerceML2\StorageInterface;
use Psr\Log\LoggerInterface;

class BaseLoader implements LoaderInterface
{

    protected $timestamp;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var StorageInterface
     */
    protected $storage;
    /**
     * @var string
     */
    protected $workDir;

    public function __construct(
        $timestamp,
        LoggerInterface $logger,
        StorageInterface $storage = null,
        $workDir = null
    ) {
        $this->timestamp = $timestamp;
        $this->logger = $logger;
        $this->storage = $storage;
        $this->workDir = $workDir;
    }

    public function loadData(DtoInterface $item)
    {
        // TODO: Implement loadData() method.
    }

    public function beforeLoad()
    {
        // TODO: Implement beforeLoad() method.
    }

    public function afterLoad()
    {
        // TODO: Implement afterLoad() method.
    }

    public function disableAllOldItems($timestamp)
    {
        // TODO: Implement disableAllOldItems() method.
    }
}
