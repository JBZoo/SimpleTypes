<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');


class ConfigTestEmpty extends Config
{
    public $default = 'i';
    public $isDebug = false;

    public function getRules()
    {
        return array('i' => []);
    }
}

class ConfigTestWeight extends Config
{
    public $default = 'gram';
    public $isDebug = true;

    public function getRules()
    {
        return array(
            'kg'   => array('rate' => function ($value, $to) {

                if ($to == 'gram') {
                    return $value * 1000;
                }

                return $value / 1000;
            }),

            'gram' => array('rate' => function ($value) {
                return $value;
            })
        );
    }
}

class ConfigTestInfo extends Config
{
    public $default = 'byte';
    public $isDebug = true;

    public function getRules()
    {
        return array(
            'byte' => array('rate' => 1),
            'kb'   => array('rate' => 1024)
        );
    }
}