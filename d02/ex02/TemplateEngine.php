<?php
declare(strict_types=1);

namespace d02\ex02;

/**
 * Class TemplateEngine
 */
class TemplateEngine
{
    /**
     * @param $fileName
     * @param $templateName
     * @param $parameters
     */
    public function createFile($fileName, $templateName, $parameters): void
    {
        $template = file_get_contents($templateName);
        if ($template === false) {
            return;
        }

        preg_match_all('/\{(.*)\}/', $template, $matches);

        $strs_to_replace = [];
        $replacements_strs = [];
        foreach ($matches[0] as $i => $match) {
            $strs_to_replace[] = $matches[0][$i];
            $replacements_strs[] = $parameters[$matches[1][$i]] ?? '';
        }
        $template = str_replace($strs_to_replace, $replacements_strs, $template);

        file_put_contents($fileName, $template);
    }
}
