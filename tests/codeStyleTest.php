<?php
/**
 * JBZoo SimpleTypes
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   SimpleTypes
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

use Symfony\Component\Finder\Finder;

/**
 * Class CodeStyleTest
 * @package JBZoo\SimpleTypes
 */
class CodeStyleTest extends Codestyle
{
    protected $_packageName = 'SimpleTypes';
    protected $_packageAuthor = 'Denis Smetannikov <denis@jbzoo.com>';

    public function testCyrillic()
    {
        $this->_excludePaths[] = 'outputTest.php';
        $this->_excludePaths[] = 'README.md';

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->notPath(basename(__FILE__))
            ->notName('*.md')
            ->notName('Money.php')
            ->exclude('tests');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isNotLike('#[А-Яа-яЁё]#ius', $content, 'File has no valid chars: ' . $file);
        }
    }
}