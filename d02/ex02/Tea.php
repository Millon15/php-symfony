<?php
declare(strict_types=1);

namespace d02\ex02;

/**
 * Class Tea
 */
class Tea extends HotBeverage
{
    public function __construct()
    {
        $this->name = 'Green tea';
        $this->price = '12';
        $this->resistence = '3';
        $this->description = 'Just green tea';
        $this->comment = 'Without suggar';
    }
}
