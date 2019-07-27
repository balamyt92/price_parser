<?php


namespace APP\CommerceML2\Schemas;


use APP\CommerceML2\DTO\PriceDTO;
use APP\CommerceML2\Loaders\PriceLoader;

class PriceSchema extends BaseSchema
{
    protected $paths = [
        'Workbook/Worksheet/Table/Row'    => PriceDTO::class,
        'Workbook/ss:Worksheet/Table/Row' => PriceDTO::class
    ];
    protected $collectionPaths = [
        'Workbook/Worksheet/Table'    => PriceLoader::class,
        'Workbook/ss:Worksheet/Table' => PriceLoader::class
    ];
}
