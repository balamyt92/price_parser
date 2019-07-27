<?php


namespace APP\CommerceML2\DTO;


use SimpleXMLElement;

class PriceDTO implements DtoInterface
{

    /**
     * @var SimpleXMLElement
     */
    public $xml;
    /**
     * @var bool
     */
    public $noEmpty;
    /*
     * @var array
     */
    public $cols;
    /**
     * @var bool
     */
    public $isCategory;
    /**
     * @var string
     */
    public $id;

    public function prepare(SimpleXMLElement $loadElementXml)
    {
        $this->xml = $loadElementXml;
        $row = (array)$this->xml;
        $this->noEmpty = is_array($row['Cell']) && count($row['Cell']) !== 0;
        $this->cols = $row['Cell'];

        if ($this->noEmpty) {
            $countNoEmptyCell = 0;
            /** @var SimpleXMLElement $cel */
            foreach ($this->cols as &$cel) {
                $atributs = $cel->attributes('ss', true);
                $cel = (array)$cel;
                if (!empty($cel['Data'])) {
                    ++$countNoEmptyCell;
                    $cel = $cel['Data'];
                    foreach ($atributs as $k => $v) {
                        if ($k === 'StyleID') {
                            $this->id = (string)$v;
                        }
                    }
                }
            }
            unset($cel);

            if ($countNoEmptyCell === 1) {
                $this->isCategory = true;
            } else if ($countNoEmptyCell > 1) {
//                var_dump('Это товар');
            } else {
                $this->noEmpty = false;
            }
        }
    }
}
