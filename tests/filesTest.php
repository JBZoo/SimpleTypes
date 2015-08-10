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
        '/**',
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
        $files = $this->getFileList(ROOT_PATH, '[/\\\\](src|tests)[/\\\\].*\.php$');

        foreach ($files as $file) {
            $content = $this->openFile($file);
            self::assertNotContains("\r", $content);
        }
    }

    public function testHeaders()
    {
        $this->excludeList[] = 'autoload.php';

        $files = $this->getFileList(ROOT_PATH, '[/\\\\](src|tests)[/\\\\].*\.php$');
        $valid = implode($this->validHeader, $this->le);

        foreach ($files as $file) {
            $content = $this->openFile($file);

            self::assertContains($valid, $content, 'File has no valid header: ' . $file);
        }
    }

    public function testCyrillic()
    {
        $this->excludeList[] = 'money.php';

        $files = $this->getFileList(ROOT_PATH, '/src/.*\.php$');

        foreach ($files as $file) {
            $content = $this->openFile($file);

            self::assertEquals(0, preg_match('/[А-Яа-яЁё]/u', $content), 'File has no valid chars: ' . $file);
        }
    }

}