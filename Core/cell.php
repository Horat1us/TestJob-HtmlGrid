<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 17.10.16
 * Time: 23:15
 */

if (!defined('system')) {
    http_response_code(403);
    echo "Method not allowed";
}

class Cell
{
    protected $places = [];
    protected $text;
    protected $align;
    protected $verticalAlign;
    protected $color;
    protected $backgroundColor;

    public static $autoField = [
        'text' => 'text',
        'align' => 'align',
        'valign' => 'verticalAlign',
        'color' => 'color',
        'bgcolor' => 'backgroundColor',
    ];

    /**
     * Cell constructor.
     * @param array $cell
     */
    public function __construct(array $cell)
    {
        self::validate($cell);

        foreach (self::$autoField as $field => $property) {
            $this->{$property} = $cell[$field];
        }

        $cells = explode(',', $cell['cells']);
        foreach ($cells as $cellString) {

            list($line, $inLineCell) = self::getCellCoordinates((int)$cellString);

            $this->locate($line, $inLineCell);
        }
    }

    public function render()
    {
        list($width, $height) = $this->sizes();
        list($cssWidth, $cssHeight) = [$width * 100, $height * 100];
        $style = "style='color:#{$this->color};background-color:#{$this->backgroundColor};text-align:{$this->align};width:{$cssWidth}px;height:{$cssHeight}px'";
        $valign = $this->verticalAlign === 'center' ? 'middle' : $this->verticalAlign;
        $sizes = "colspan=\"{$width}\" rowspan=\"{$height}\" valign=\"{$valign}\"";
        return "<td $style $sizes>{$this->text}</td>";
    }

    public $width;

    /**
     * @return int
     */
    public function width()
    {
        asort($this->places);
        $pointIterator = 0;
        $leftTopPoint = $this->places[$pointIterator];

        list($line,) = self::getCellCoordinates($leftTopPoint);

        while (isset($this->places[++$pointIterator])
            && self::getCellCoordinates($this->places[$pointIterator])[0] === $line) {
        }

        return $this->width = $pointIterator;
    }

    public $height;

    /**
     * @param integer|null $width
     * @return float
     */
    public function height(int $width = null)
    {
        if (!$width) {
            $width = $this->width();
        }

        return $this->height = (count($this->places) / $width);
    }

    public function sizes()
    {
        $width = $this->width();
        $height = $this->height($width);

        return [$width, $height];
    }

    /**
     * @param integer $line
     * @param integer $cell
     * @return bool
     */
    public function isLocated(int $line, int $cell)
    {
        return in_array($this->getCellNumber($line, $cell), $this->places);
    }

    /**
     * @param integer $line
     * @param integer $cell
     * @return bool|integer
     */
    public function locate(int $line, int $cell)
    {
        if ($this->isLocated($line, $cell)) {
            return false;
        }

        return $this->places[] = $this->getCellNumber($line, $cell);
    }

    /**
     * @param int $line
     * @param int $cell
     * @return bool
     */
    public function unlocete(int $line, int $cell)
    {
        $value = array_search($this->getCellNumber($line, $cell), $this->places);
        if ($value === FALSE) {
            return false;
        }
        unset($this->places[$value]);
        return true;
    }

    /**
     * @param $line
     * @param $cell
     * @return integer
     */
    protected static function getCellNumber(int $line, int $cell)
    {
        return --$line * CELLS_PER_LINE + $cell;
    }

    /**
     * @param $cellNumber
     * @return array
     */
    protected static function getCellCoordinates($cellNumber)
    {
        $line = ceil($cellNumber / CELLS_PER_LINE);
        return [$line, $cellNumber - CELLS_PER_LINE * --$line];
    }

    /** @var array $requiredFields */
    public static $requiredFields = ['text', 'cells', 'align', 'valign', 'color', 'bgcolor'];

    /**
     * @param array $cellProperties
     * @return bool
     * @throws InvalidCodeSampleException
     * @throws RenderException
     */
    public static function validate(array $cellProperties) :bool
    {
        $validateHex = function (string $hexColor) :bool {
            $match = preg_match('/#{0,1}([a-f0-9]{3}){1,2}\b/i', $hexColor);
            return (bool)$match;
        };
        $validateFields = [
            'align' => function ($align) : bool {
                return in_array($align, ['left', 'center', 'right']);
            },
            'valign' => function ($verticalAlign) : bool {
                return in_array($verticalAlign, ['top', 'center', 'bottom']);
            },
            'color' => $validateHex,
            'bgcolor' => $validateHex,
            'cells' => function ($cells) {
                $cells = explode(',', $cells);

                foreach ($cells as $cell) {
                    if (!is_numeric($cell)) {
                        throw new InvalidCodeSampleException("Wrong cell: ${$cell}");
                    }
                }
                return true;
            }
        ];

        foreach ($validateFields as $field => $callback) {
            if (!isset($cellProperties[$field])) {
                throw new InvalidCodeSampleException("Cell property {$field} does not exists");
            }

            if (is_callable($callback)) {
                $result = (bool)$callback($cellProperties[$field]);
                if (!$result) {
                    throw new InvalidCodeSampleException(
                        "{$field} property (" . htmlentities($cellProperties[$field]) . ") did not pass the validation"
                    );
                }
            } else {
                throw new RenderException("Unexpected validation rule");
            }
        }
        return true;
    }

}