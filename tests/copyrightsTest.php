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
 * Class ÑopyrightsTest
 * @package SmetDenis\SimpleTypes
 */
class ÑopyrightsTest extends PHPUnit
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

    public function testHeaders()
    {
        $this->excludeList[] = 'demo.php';
        $this->excludeList[] = 'autoload.php';

        $files = $this->getFileList(ROOT_PATH, '#\.php$#i');
        $valid = implode($this->validHeader, $this->le);

        $length = strlen($valid);

        foreach ($files as $file) {
            $content = $this->openFile($file);

            if (function_exists('mb_substr')) {
                $content = mb_substr($content, 0, $length);
            } else {
                $content = substr($content, 0, $length);
            }

            $this->assertEquals($valid, $content, 'File has novalid header: ' . $file);
        }
    }

}
