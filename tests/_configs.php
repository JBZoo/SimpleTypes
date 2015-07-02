<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');


class ConfigTestEmpty extends Config
{
    public $default = 'usd';
    public $debug = true;

    public function getRules()
    {
        return array('usd' => []);
    }
}

class ConfigTestWeight extends Config
{
    public $default = 'gram';
    public $debug = true;

    public function getRules()
    {
        return array(
            'kg'   => array('rate' => 1000),
            'gram' => array('rate' => 1)
        );
    }
}

class ConfigTestInfo extends Config
{
    public $default = 'byte';
    public $debug = true;

    public function getRules()
    {
        return array(
            'byte' => array('rate' => 1),
            'kb'   => array('rate' => 1024)
        );
    }
}