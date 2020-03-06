<?php
declare(strict_types=1);

namespace d02\ex03;

class Elem
{
    /** @var string */
    private $tag;

    /** @var string */
    private $content;

    /** @var array */
    private $elemInside = [];

    /**
     * Elem constructor.
     *
     * @param string $tag
     * @param string $content
     */
    public function __construct(string $tag, string $content = '')
    {
        $this->tag = $tag;
        $this->content = $content;
    }

    /**
     * @param self $newElem
     */
    public function pushElement(self $newElem): void
    {
        $this->elemInside[] = $newElem;
    }

    /**
     * @return string
     */
    public function getHTML(): string
    {
        $html = "<{$this->tag}>";
        $html .= $this->content;
        foreach ($this->elemInside as $elem) {
            $html .= $elem->getHTML();
        }
        $html .= "</{$this->tag}>";

        return $html;
    }
}
