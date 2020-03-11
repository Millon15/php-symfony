<?php
declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Player
{
    /**
     * @Assert\NotBlank
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $level = 1;

    /**
     * @var int
     */
    private $health = 2;

    /**
     * @var int
     */
    private $attack = 1;

    public function levelUp(): void
    {
        $this->level += 1;
        $this->health += 2;
        $this->attack += 1;
    }

    /**
     * @param string $name
     *
     * @return Player
     */
    public function setName(string $name): Player
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param int $level
     *
     * @return Player
     */
    public function setLevel(int $level): Player
    {
        $this->level = $level;

        return $this;
}

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $health
     *
     * @return Player
     */
    public function setHealth(int $health): Player
    {
        $this->health = $health;

        return $this;
    }

    /**
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * @param int $attack
     *
     * @return Player
     */
    public function setAttack(int $attack): Player
    {
        $this->attack = $attack;

        return $this;
    }

    /**
     * @return int
     */
    public function getAttack(): int
    {
        return $this->attack;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'name' => $this->getName(),
            'level' => $this->getLevel(),
            'health' => $this->getHealth(),
            'attack' => $this->getAttack()
        ];
    }
}
