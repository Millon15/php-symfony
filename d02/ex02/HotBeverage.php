<?php
declare(strict_types=1);

namespace d02\ex02;

/**
 * Class HotBeverage
 */
abstract class HotBeverage
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $price;

    /** @var string */
    protected $resistence;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getResistence(): string
    {
        return $this->resistence;
    }
}
