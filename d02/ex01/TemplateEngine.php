<?php
declare(strict_types=1);

namespace d02\ex01;

/**
 * Class TemplateEngine
 */
class TemplateEngine
{
    /**
     * @param      $fileName
     * @param Text $text
     */
    public function createFile($fileName, Text $text): void
    {
        $template = '<!DOCTYPE html><html lang="en"><head><title>What?</title></head><body>';
        ob_start();
        $text->printStrings();
        $template .= ob_get_clean();
        $template .= '</body></html>';

        if (file_put_contents($fileName, $template) === false) {
            throw new \RuntimeException("Can't put contents into file with name: $fileName");
        }
    }
}
