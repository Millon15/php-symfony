<?php
declare(strict_types=1);

namespace d02\ex02;

/**
 * Class Tea
 */
class Tea extends HotBeverage
{
    /** @var string */
    private $description;

    /** @var string */
    private $comment;

    public function __construct()
    {
        $this->name = 'Green tea';
        $this->price = '12';
        $this->resistence = '2';
        $this->description = 'Just green tea';
        $this->comment = 'Without suggar';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }
}
