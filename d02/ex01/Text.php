<?php
declare(strict_types=1);

namespace d02\ex01;

/**
 * Class Text
 * @package d02\ex01
 */
class Text
{
    private $strings;

    /**
     * Text constructor.
     *
     * @param array $strings
     */
    public function __construct(array $strings = [])
    {
        $this->strings = $strings;
    }

    /**
     * @param string $str
     */
    public function addString(string $str): void
    {
        $this->strings[] = $str;
    }

    /**
     * @return void
     */
    public function printStrings(): void
    {
        $to_return = '';
        foreach ($this->strings as $string) {
            $to_return .= "<p>$string</p>";
        }

        echo $to_return;
    }
}
