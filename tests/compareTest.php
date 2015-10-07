<?php
/**
 * SimpleTypes
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

namespace JBZoo\SimpleTypes;

/**
 * Class compareTest
 * @package JBZoo\SimpleTypes
 */
class CompareTest extends PHPUnit
{

    public function testSimple()
    {
        $this->batchEquals(array(
            array(true, $this->val(1)->compare(1, '=')),
            array(true, $this->val(1)->compare(1, '==')),
            array(true, $this->val(1)->compare(1, '===')),
            array(true, $this->val(1)->compare(1, '>=')),
            array(true, $this->val(1)->compare(1, '<=')),

            array(false, $this->val(1)->compare(1, '<')),
            array(false, $this->val(1)->compare(1, '!=')),
            array(false, $this->val(1)->compare(1, '!==')),
            array(false, $this->val(1)->compare(1, '>')),
        ));
    }

    public function testComplex()
    {
        $v1 = $this->val(1.5);
        $v2 = $this->val('5.6');

        $this->batchEquals(array(
            array(false, $v1->compare($v2, ' =')),
            array(true, $v1->compare($v2, '< ')),
            array(true, $v1->compare($v2, ' <= ')),
            array(false, $v1->compare($v2, ' >= ')),
            array(false, $v1->compare($v2, '>')),
        ));
    }

    public function testRules()
    {
        $usd = $this->val('1 usd');
        $eur = $this->val('1 eur');

        $this->batchEquals(array(
            array(false, $usd->compare($eur, '=')),
            array(true, $usd->compare($eur, '!=')),
            array(true, $usd->compare($eur, '<')),
            array(true, $usd->compare($eur, '<=')),
            array(false, $usd->compare($eur, '>')),
            array(false, $usd->compare($eur, '>=')),
        ));

        // after convert
        $eur->convert('usd');
        $usd->convert('eur');

        $this->batchEquals(array(
            array(false, $usd->compare($eur, '==')),
            array(true, $usd->compare($eur, '!==')),
            array(true, $usd->compare($eur, '<')),
            array(true, $usd->compare($eur, '<=')),
            array(false, $usd->compare($eur, '>')),
            array(false, $usd->compare($eur, '>=')),
        ));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testUndefined()
    {
        $this->val('usd')->compare(0, 'undefined');
    }
}
