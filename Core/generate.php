<?php
if (!defined('system')) {
    http_response_code(403);
    echo "Method not allowed";
}
define('CELLS_PER_LINE', 3);
define("LINES_PER_TABLE", 3);
require_once(SYSTEM . '/Core/exceptions.php');
require_once(SYSTEM . '/Core/cell.php');

class Generate
{
    protected $lines;
    protected $cells = [];

    /**
     * Generator constructor.
     * @param array $codeSample
     */
    public function __construct(array $codeSample)
    {
        self::validate($codeSample);

        foreach ($codeSample as $cell) {
            $this->cells[] = new Cell($cell);
        }
    }

    public $render;

    /**
     * @param $htmlAttributes
     * @return mixed
     */
    public function render(array $htmlAttributes = [])
    {

        $cells = $this->cells;
        $renderedCells = [];
        $htmlAttributes['style'] = "width:" . CELLS_PER_LINE * 100 . 'px;height:' . LINES_PER_TABLE * 100 . 'px;';

        $this->openTable($htmlAttributes);

        for ($line = 1; $line <= LINES_PER_TABLE; $line++) {
            $this->openLine();
            for ($position = 1; $position <= CELLS_PER_LINE; $position++) {
                $isOccupied = self::findOccupant($renderedCells, $line, $position);
                if ($isOccupied !== FALSE) {
                    continue;
                }
                $occupant = self::findOccupant($cells, $line, $position);
                if ($occupant === FALSE) {
                    $this->render .= "<td style='width:100px;'></td>";
                    continue;
                }

                list($occupant, $key) = $occupant;
                /** @var Cell $occupant */

                $this->render .= $occupant->render();
                unset($cells[$key]);

                $renderedCells[] = $occupant;
            }
            $this->closeLine();
        }
        $this->closeTable();

        return $this->render;
    }

    protected function openTable($htmlAttributes)
    {
        $htmlAttributesString = '';
        foreach ($htmlAttributes as $name => $value) {
            $htmlAttributesString .= $name . ($value === TRUE ? '' : "={$value}") . ' ';
        }
        return $this->render = "<table {$htmlAttributesString}>";
    }

    protected function closeTable()
    {
        return $this->render .= '</table>';
    }

    protected function openLine()
    {
        return $this->render .= "<tr>";
    }

    protected function closeLine()
    {
        return $this->render .= "</tr>";
    }

    /**
     * @param $codeSample
     * @return bool
     * @throws InvalidCodeSampleException
     */
    public static function validate($codeSample)
    {
        foreach ($codeSample as $cell) {
            if (!is_array($cell)) {
                throw new InvalidCodeSampleException("Cell is not an array");
            }
            $unsettedProperties = [];
            foreach (Cell::$requiredFields as $field) {
                if (!$cell[$field]) {
                    $unsettedProperties[$field] = true;
                }
            }
            if (count($unsettedProperties)) {
                $unsettedProperties = implode($unsettedProperties, ', ');
                throw new InvalidCodeSampleException("Properties {$unsettedProperties} does not exists");
            }
        }

        return true;
    }

    public static function findOccupant(array $cellsCollection, int $line, int $position)
    {
        /** @var Cell $cell */
        foreach ($cellsCollection as $key => $cell) {
            if ($cell->isLocated($line, $position)) {
                return [$cell, $key];
            }
        }
        return false;
    }
}
