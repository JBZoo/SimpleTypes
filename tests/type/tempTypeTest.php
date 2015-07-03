<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/typeTest.php');


/**
 * Class tempTypeTest
 * @package SmetDenis\SimpleTypes
 */
class tempTypeTest extends typeTest
{

    protected $_type = 'temp';


    function Create()
    {
        $val = $this->_('300C')
                    ->convert('F')
                    ->convert('K')
                    ->convert('R')
                    ->convert('De')
                    ->convert('N')
                    ->convert('RE')
                    ->convert('RO')
                    ->convert('c');

        dump($val->logs());

    }


}