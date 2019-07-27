<?php

namespace APP\CommerceML2\DTO;

use SimpleXMLElement;

interface DtoInterface
{
    public function prepare(SimpleXMLElement $loadElementXml);
}
