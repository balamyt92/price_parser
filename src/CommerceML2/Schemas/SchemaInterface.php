<?php

namespace APP\CommerceML2\Schemas;

use APP\CommerceML2\StorageInterface;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;

interface SchemaInterface
{
    public function __construct(LoggerInterface $logger = null, StorageInterface $storage = null, $workDir = null);

    public function setTimestamp($timestamp);

    public function setStorage(StorageInterface $storage);

    public function hasPath($path);

    public function dispatch($path, SimpleXMLElement $loadElementXml);

    public function parseEnd();

    public function parseStart();

    public function dispatchCollectionEnd($collectionPath);

    public function hasCollectionPath($collectionPath);

    public function dispatchCollectionStart($collectionPath, $attributes);

    public function disableAll();

    /**
     * @param $path
     *
     * @return array
     */
    public function getAttributesForCollection($path);
}
