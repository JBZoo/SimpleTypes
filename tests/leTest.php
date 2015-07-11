<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class LETest
 * @package SmetDenis\SimpleTypes
 */
class LETest extends PHPUnit
{

    protected $excludeList = array(
        '.',
        '..',
        '.idea',
        '.git',
        'build',
        'vendor',
        'reports'
    );

    public function testFiles()
    {
        $files = $this->getFileList(ROOT_PATH);

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $this->assertFalse(strpos($content, "\r\n"), 'File contains CRLF: ' . $file);
            $this->assertFalse(strpos($content, "\n\r"), 'File contains LFCR: ' . $file);
        }
    }

    protected function getFileList($dir, &$results = array())
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = $dir . DIRECTORY_SEPARATOR . $value;

            if (!is_dir($path)) {
                $results[] = $path;
            } elseif (is_dir($path) && !in_array($value, $this->excludeList, true)) {
                $this->getFileList($path, $results);
            }
        }

        return $results;
    }
}
