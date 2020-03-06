<?php
declare(strict_types=1);

namespace d02\ex04;

class Elem
{
    private static $possibleTags = [
        'meta',
        'img',
        'hr',
        'br',
        'html',
        'head',
        'body',
        'title',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'p',
        'span',
        'div',
        'table',
        'tr',
        'th',
        'td',
        'ul',
        'ol',
        'li',
    ];

    /** @var string */
    private $tag;

    /** @var string */
    private $content;

    /** @var array */
    private $elemInside = [];

    /** @var array */
    private $attributes;

    /**
     * Elem constructor.
     *
     * @param string $tag
     * @param string $content
     * @param array  $attributes
     */
    public function __construct(string $tag, string $content = '', array $attributes = [])
    {
        if (!\in_array($tag, self::$possibleTags, true)) {
            throw new MyException("Unsupported tag with name: $tag");
        }
        $this->tag = $tag;
        $this->content = $content;
        $this->attributes = $attributes;
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
        $html = "<{$this->tag}";
        foreach ($this->attributes as $attributeName => $attribute) {
            if (!\is_string($attributeName)) {
                throw new MyException('Attribute name cannot be of a type different than string.');
            }
            if (!\is_string($attribute)) {
                throw new MyException('Attribute body cannot be of a type different than string.');
            }

            $html .= ' ' . "$attributeName=\"$attribute\"";
        }
        $html .= '>';

        $html .= $this->content;
        foreach ($this->elemInside as $elem) {
            $html .= $elem->getHTML();
        }
        $html .= "</{$this->tag}>";

        return $html;
    }
}
