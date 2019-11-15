<?php
declare(strict_types=1);

namespace d02\ex02;

/**
 * Class Cofee
 */
class Cofee extends HotBeverage
{
    public function __construct()
    {
        $this->name = 'Espresso';
        $this->price = '12';
        $this->resistence = '3';
        $this->description = 'Easy espresso';
        $this->comment = 'Easier than it might seem';
    }
}
