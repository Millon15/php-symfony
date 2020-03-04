<?php
declare(strict_types=1);

namespace d02\ex00;

/**
 * Class TemplateEngine
 */
class TemplateEngine
{
    /**
     * @param $fileName
     * @param $templateName
     * @param $parameters
     *
     * @throws \RuntimeException
     */
    public function createFile(string $fileName, string $templateName, array $parameters): void
    {
        $template = file_get_contents($templateName);
        if ($template === false) {
            throw new \RuntimeException("Can't get contents from file with name: $templateName");
        }

        preg_match_all('/\{(.*)\}/', $template, $matches);

        $strs_to_replace = [];
        $replacements_strs = [];
        foreach ($matches[0] as $i => $match) {
            $strs_to_replace[] = $matches[0][$i];
            $replacements_strs[] = $parameters[$matches[1][$i]] ?? '';
        }
        $template = str_replace($strs_to_replace, $replacements_strs, $template);

        if (file_put_contents($fileName, $template) === false) {
            throw new \RuntimeException("Can't put contents into file with name: $fileName");
        }
    }
}
