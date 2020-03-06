<?php
declare(strict_types=1);

namespace d02\ex03;

/**
 * Class TemplateEngine
 */
class TemplateEngine
{
    /** @var Elem */
    private $elem;

    public function __construct(Elem $elem)
    {
        $this->elem = $elem;
    }

    /**
     * @param string $fileName
     */
    public function createFile(string $fileName): void
    {
        if (file_put_contents($fileName, $this->elem->getHTML()) === false) {
            throw new \RuntimeException("Can't put contents into file with name: $fileName");
        }
    }
}
