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
        $to_return = '<!DOCTYPE html><html lang="en"><head><title>What?</title></head><body>';
        ob_start();
        $text->printStrings();
        $to_return .= ob_get_contents();
        ob_end_clean();
        $to_return .= '</body></html>';

        file_put_contents($fileName, $to_return);
    }
}
