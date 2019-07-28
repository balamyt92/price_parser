<?php


namespace APP\CommerceML2\Loaders;


use APP\CommerceML2\DTO\DtoInterface;
use APP\CommerceML2\DTO\PriceDTO;

class PriceLoader extends BaseLoader
{
    /**
     * @var PriceDTO
     */
    private $previous;
    private static $rows = [];
    private $currentCategoryString = '';
    private $categoryDeep = [];
    private $categoryNames = [];

    public function loadData(DtoInterface $item)
    {
        /** @var PriceDTO $item */
        if ($item->noEmpty) {
            if ($item->isCategory) {
                if (in_array($item->id, $this->categoryDeep, true)) {
                    $key = array_search($item->id, $this->categoryDeep, true);
                    $this->categoryDeep = array_slice($this->categoryDeep, 0, $key);
                    $this->categoryNames = array_slice($this->categoryNames, 0, $key);
                }

                $this->categoryDeep[] = $item->id;
                $this->categoryNames[] = $this->getCategoryName($item);

                $this->currentCategoryString = implode(' / ', $this->categoryNames);
            } else {
                self::$rows[] = array_merge([$this->currentCategoryString], $item->cols);
            }
            $this->previous = $item;
        }
    }

    public function beforeLoad()
    {
    }

    public function afterLoad()
    {
        file_put_contents('result.xml', $this->wrapXml($this->makeRowsXml(self::$rows)));
    }

    private function getCategoryName(PriceDTO $item)
    {
        return trim(array_reduce($item->cols, function ($acc, $item) {
            $acc .= empty($item) || is_array($item) ? '' : $item;

            return $acc;
        }, ''));
    }

    public function makeRowsXml($rows)
    {
        $result = '';
        foreach ($rows as $cels) {
            $cels_xml = '';
            foreach ($cels as $cel) {
                if (is_string($cel)) {
                    $cel = htmlspecialchars(trim($cel));
                    $cels_xml .= "<Cell><Data ss:Type=\"String\">{$cel}</Data> </Cell>";
                } else {
                    $cels_xml .= '<Cell/>';
                }
            }
            $result .= "<Row ss:Height=\"12,8126\"> {$cels_xml} </Row>";
        }

        return $result;
    }

    public function wrapXml($rowsXml)
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
          xmlns:c="urn:schemas-microsoft-com:office:component:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40"
          xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
          xmlns:x2="http://schemas.microsoft.com/office/excel/2003/xml" xmlns:x="urn:schemas-microsoft-com:office:excel"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
        <Colors>
            <Color>
                <Index>3</Index>
                <RGB>#000000</RGB>
            </Color>
            <Color>
                <Index>4</Index>
                <RGB>#0000ee</RGB>
            </Color>
            <Color>
                <Index>5</Index>
                <RGB>#006600</RGB>
            </Color>
            <Color>
                <Index>6</Index>
                <RGB>#333333</RGB>
            </Color>
            <Color>
                <Index>7</Index>
                <RGB>#808080</RGB>
            </Color>
            <Color>
                <Index>8</Index>
                <RGB>#996600</RGB>
            </Color>
            <Color>
                <Index>9</Index>
                <RGB>#c0c0c0</RGB>
            </Color>
            <Color>
                <Index>10</Index>
                <RGB>#cc0000</RGB>
            </Color>
            <Color>
                <Index>11</Index>
                <RGB>#ccffcc</RGB>
            </Color>
            <Color>
                <Index>12</Index>
                <RGB>#dddddd</RGB>
            </Color>
            <Color>
                <Index>13</Index>
                <RGB>#ffcccc</RGB>
            </Color>
            <Color>
                <Index>14</Index>
                <RGB>#ffffcc</RGB>
            </Color>
            <Color>
                <Index>15</Index>
                <RGB>#ffffff</RGB>
            </Color>
        </Colors>
    </OfficeDocumentSettings>
    <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
        <WindowHeight>9000</WindowHeight>
        <WindowWidth>13860</WindowWidth>
        <WindowTopX>240</WindowTopX>
        <WindowTopY>75</WindowTopY>
        <ProtectStructure>False</ProtectStructure>
        <ProtectWindows>False</ProtectWindows>
    </ExcelWorkbook>
    <Styles>
        <Style ss:ID="Default" ss:Name="Default"/>
        <Style ss:ID="Heading_20__28_user_29_" ss:Name="Heading (user)">
            <Font ss:Bold="1" ss:Color="#000000" ss:Size="24"/>
        </Style>
        <Style ss:ID="Heading_20_1" ss:Name="Heading 1">
            <Font ss:Bold="1" ss:Color="#000000" ss:Size="18"/>
        </Style>
        <Style ss:ID="Heading_20_2" ss:Name="Heading 2">
            <Font ss:Bold="1" ss:Color="#000000" ss:Size="12"/>
        </Style>
        <Style ss:ID="Text" ss:Name="Text"/>
        <Style ss:ID="Note" ss:Name="Note">
            <Font ss:Color="#333333" ss:Size="10"/>
        </Style>
        <Style ss:ID="Footnote" ss:Name="Footnote">
            <Font ss:Color="#808080" ss:Italic="1" ss:Size="10"/>
        </Style>
        <Style ss:ID="Hyperlink" ss:Name="Hyperlink">
            <Font ss:Color="#0000ee" ss:Size="10" ss:Underline="Single"/>
        </Style>
        <Style ss:ID="Status" ss:Name="Status"/>
        <Style ss:ID="Good" ss:Name="Good">
            <Font ss:Color="#006600" ss:Size="10"/>
        </Style>
        <Style ss:ID="Neutral" ss:Name="Neutral">
            <Font ss:Color="#996600" ss:Size="10"/>
        </Style>
        <Style ss:ID="Bad" ss:Name="Bad">
            <Font ss:Color="#cc0000" ss:Size="10"/>
        </Style>
        <Style ss:ID="Warning" ss:Name="Warning">
            <Font ss:Color="#cc0000" ss:Size="10"/>
        </Style>
        <Style ss:ID="Error" ss:Name="Error">
            <Font ss:Bold="1" ss:Color="#ffffff" ss:Size="10"/>
        </Style>
        <Style ss:ID="Accent" ss:Name="Accent">
            <Font ss:Bold="1" ss:Color="#000000" ss:Size="10"/>
        </Style>
        <Style ss:ID="Accent_20_1" ss:Name="Accent 1">
            <Font ss:Bold="1" ss:Color="#ffffff" ss:Size="10"/>
        </Style>
        <Style ss:ID="Accent_20_2" ss:Name="Accent 2">
            <Font ss:Bold="1" ss:Color="#ffffff" ss:Size="10"/>
        </Style>
        <Style ss:ID="Accent_20_3" ss:Name="Accent 3">
            <Font ss:Bold="1" ss:Color="#000000" ss:Size="10"/>
        </Style>
        <Style ss:ID="co1"/>
        <Style ss:ID="ta1"/>
    </Styles>
    <ss:Worksheet ss:Name="Лист1">
        <Table ss:StyleID="ta1">
            <Column ss:Span="6" ss:Width="64,0063"/>
            {$rowsXml}
        </Table>
        <x:WorksheetOptions/>
    </ss:Worksheet>
</Workbook>
XML;

    }
}
