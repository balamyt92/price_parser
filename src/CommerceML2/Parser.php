<?php

namespace APP\CommerceML2;

use APP\CommerceML2\Schemas\SchemaInterface;
use RuntimeException;
use SimpleXMLElement;
use SplFileObject;
use XMLReader;

class Parser
{
    /**
     * @var SplFileObject
     */
    private $file;
    private $schema;
    private $xmlReader;
    private $path = [];

    /**
     * Parser constructor.
     *
     * @param string          $file
     * @param SchemaInterface $schema
     */
    public function __construct($file, SchemaInterface $schema)
    {
        if (!file_exists($file)) {
            throw new RuntimeException("file {$file} not exist");
        }
        $this->file = new SplFileObject($file);
        $this->schema = $schema;
        $this->xmlReader = new XMLReader();
    }

    public function parse()
    {
        $this->xmlReader->open($this->file->getRealPath());
        $this->schema->parseStart();
        while ($this->xmlReader->read()) {
            if ($this->xmlReader->nodeType === XMLReader::END_ELEMENT) {
                $path = implode('/', $this->path);
                if ($this->schema->hasCollectionPath($path)) {
                    $this->schema->dispatchCollectionEnd($path);
                }
                array_pop($this->path);
                continue;
            }
            if ($this->xmlReader->nodeType === XMLReader::ELEMENT) {
                $this->path[] = $this->xmlReader->name;
                $path = implode('/', $this->path);
                if ($this->xmlReader->isEmptyElement) {
                    array_pop($this->path);
                }
                if ($this->schema->hasPath($path)) {
                    $this->schema->dispatch($path, $this->loadElementXml());
                } else if ($this->schema->hasCollectionPath($path)) {
                    $attributes = [];
                    foreach ($this->schema->getAttributesForCollection($path) as $attributeName) {
                        $attributes[$attributeName] = $this->xmlReader->getAttribute($attributeName);
                    }
                    $this->schema->dispatchCollectionStart($path, $attributes);
                }
            }
        }
        $this->schema->parseEnd();
        $this->xmlReader->close();
    }

    /**
     * @return SimpleXMLElement
     */
    protected function loadElementXml()
    {
        $xml = $this->xmlReader->readOuterXml();

        return simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>' . $xml);
    }
}
