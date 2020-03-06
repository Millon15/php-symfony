<?php
declare(strict_types=1);

namespace d02\ex05;

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

    /**
     * @throws MyException
     */
    public function validPage(): bool
    {
        try {
            switch ($this->tag):
                case 'p':
                    if (\count($this->elemInside) !== 0) {
                        throw new MyException();
                    }
                    break;

                case 'table':
                    foreach ($this->elemInside as $elem) {
                        if ($elem->tag !== 'tr'):
                            throw new MyException();
                        endif;
                    }
                    break;

                case 'tr':
                    foreach ($this->elemInside as $elem) {
                        if ($elem->tag !== 'th'):
                            throw new MyException();
                        endif;
                    }
                    break;

                case 'ul':
                case 'ol':
                    foreach ($this->elemInside as $elem) {
                        if ($elem->tag !== 'li'):
                            throw new MyException();
                        endif;
                    }
                    break;

                default:
                    $this->validateHtmlTag();
                    break;
            endswitch;

            foreach ($this->elemInside as $elem) {
                if ($elem->validPage() === false):
                    return false;
                endif;
            }
        } catch (MyException $e) {
            return false;
        }

        return true;
    }

    /**
     * @throws MyException
     */
    private function validateHtmlTag(): void
    {
        $page = $this->getHTML();
        if (!preg_match("/^<html.*<\/html>$/ms", $page)) {
            throw new MyException();
        }

        if (substr_count($page, '<body') !== 1
            && substr_count($page, '</body>') !== 1
        ) {
            throw new MyException();
        }
        if (substr_count($page, '<head') !== 1
            && substr_count($page, '</head>') !== 1
        ) {
            throw new MyException();
        }
        if (substr_count($page, '<title') !== 1
            && substr_count($page, '</title>') !== 1
        ) {
            throw new MyException();
        }
        if (substr_count($page, '<meta charset=') !== 1) {
            throw new MyException();
        }
    }
}
