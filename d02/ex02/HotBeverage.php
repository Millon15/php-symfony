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

    /** @var string */
    protected $description;

    /** @var string */
    protected $comment;

    /**
     * @param $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property ?? null;
    }
}
