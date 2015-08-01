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
class FilesTest extends PHPUnit
{

    protected $le = "\n";

    protected $validHeader = array(
        '<?php',
        '/**',
        ' * SimpleTypes',
        ' *',
        ' * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.',
        ' *',
        ' * @package   SimpleTypes',
        ' * @author    Denis Smetannikov <denis@jbzoo.com>',
        ' * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>',
        ' * @link      http://github.com/smetdenis/simpletypes',
        ' */',
        '',
        'namespace SmetDenis\SimpleTypes;',
        '',
        '/**'
    );

    protected $excludeList = array(
        '.',
        '..',
        '.idea',
        '.git',
        'build',
        'vendor',
        'reports',
        'composer.phar',
        'composer.lock',
    );

    public function testFiles()
    {
        $files = $this->getFileList(ROOT_PATH);

        foreach ($files as $file) {
            $content = $this->openFile($file);
            self::assertNotContains("\r", $content);
        }
    }

    public function testHeaders()
    {
        $this->excludeList[] = 'demo.php';
        $this->excludeList[] = 'autoload.php';

        $files = $this->getFileList(ROOT_PATH, '#\.php$#i');
        $valid = implode($this->validHeader, $this->le);

        foreach ($files as $file) {
            $content = $this->openFile($file);

            self::assertContains($valid, $content, 'File has novalid header: ' . $file);
        }
    }

}