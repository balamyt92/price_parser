<?php

namespace APP\CommerceML2\Schemas;

use Exception;
use APP\CommerceML2\DTO\DtoInterface;
use APP\CommerceML2\Loaders\LoaderInterface;
use APP\CommerceML2\StorageInterface;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;

class BaseSchema implements SchemaInterface
{
    protected $paths = [];
    protected $collectionPaths = [];
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var StorageInterface
     */
    protected $storage;
    /**
     * @var LoaderInterface
     */
    protected $loader;
    protected $timestamp;
    /**
     * @var null|string
     */
    protected $workDir;

    /**
     * CatalogSchema constructor.
     *
     * @param LoggerInterface       $logger
     * @param StorageInterface|null $storage
     * @param string|null           $workDir
     */
    public function __construct(LoggerInterface $logger = null, StorageInterface $storage = null, $workDir = null)
    {
        $this->logger = $logger;
        $this->storage = $storage;
        $this->workDir = $workDir;
    }

    public function hasPath($path)
    {
        return array_key_exists($path, $this->paths);
    }

    public function dispatch($path, SimpleXMLElement $loadElementXml)
    {
        try {
            if ($this->loader) {
                /** @var DtoInterface $dto */
                $dto = new $this->paths[$path];
                $dto->prepare($loadElementXml);
                $this->loader->loadData($dto);
            }
        } catch (Exception $e) {
            $this->log($e);
        }
    }

    private function log(Exception $e)
    {
        if ($this->logger) {
            $this->logger->error($e->getMessage());
        }
    }

    public function parseEnd()
    {
    }

    public function parseStart()
    {
    }

    public function dispatchCollectionEnd($collectionPath)
    {
        if ($this->loader) {
            $this->loader->afterLoad();
        }
    }

    public function hasCollectionPath($collectionPath)
    {
        return array_key_exists($collectionPath, $this->collectionPaths);
    }

    public function dispatchCollectionStart($collectionPath, $attributes)
    {
        $loaderClass = $this->collectionPaths[$collectionPath];
        if ($loaderClass) {
            $this->loader = new $loaderClass($this->timestamp, $this->logger, $this->storage, $this->workDir);
            $this->loader->beforeLoad();
        } else {
            $this->loader = null;
        }
    }

    public function disableAll()
    {
        foreach ($this->collectionPaths as $loaderClass) {
            if ($loaderClass) {
                /** @var LoaderInterface $loader */
                $loader = new $loaderClass($this->timestamp, $this->logger, $this->storage, $this->workDir);
                $loader->disableAllOldItems($this->timestamp);
            }
        }
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = (int)$timestamp;
    }

    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getAttributesForCollection($path)
    {
        return [];
    }
}
