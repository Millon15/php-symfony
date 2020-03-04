<?php
declare(strict_types=1);

namespace d02\ex02;

/**
 * Class TemplateEngine
 */
class TemplateEngine
{
    public static $templateVars = [
        'nom' => 'name',
        'prix' => 'price',
        'resistance' => 'resistence',
        'description' => 'description',
        'commentaire' => 'comment',
    ];

    /**
     * @param HotBeverage $text
     *
     * @throws \ReflectionException
     */
    public function createFile(HotBeverage $text): void
    {
        $reflection = new \ReflectionClass($text);
        $templateName = 'template.html';
        $fileName = $reflection->getShortName();

        $template = file_get_contents($templateName);
        if ($template === false) {
            throw new \RuntimeException("Can't get contents from file with name: $templateName");
        }

        preg_match_all('/\{(.*)\}/', $template, $matches);
        $stringsToReplace = $matches[0];
        foreach ($matches[1] as &$match) {
            $match = 'get' . ucfirst(self::$templateVars[$match]);
            $match = $text->$match();
        }
        unset($match);
        $replacements= $matches[1];
        $template = str_replace($stringsToReplace, $replacements, $template);
        if (file_put_contents($fileName, $template) === false) {
            throw new \RuntimeException("Can't put contents into file with name: $fileName");
        }
    }
}
