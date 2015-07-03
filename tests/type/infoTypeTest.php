<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/typeTest.php');


/**
 * Class infoTypeTest
 * @package SmetDenis\SimpleTypes
 */
class infoTypeTest extends typeTest
{

    protected $_type = 'info';


    function testCreate()
    {
        $val = $this->_('1024Gb')
                    ->convert('bit')
                    ->convert('kb')
                    ->convert('byte')
                    ->convert('bit')
                    ->convert('bit')
                    ->convert('byte')
                    ->convert('kb')
                    ->convert('gb');

        $this->assertEquals('1024 gb', $val->dump(false));

    }


}