<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <smet.denis@gmail.com>.
 *
 * @package    SimpleTypes
 * @author     Denis Smetannikov <smet.denis@gmail.com>
 * @copyright  2015 Denis Smetannikov <smet.denis@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class Type
 * @package SmetDenis\SimpleTypes
 */
class Parser
{
    const ERROR_FATAL   = 1;
    const ERROR_NOTICE  = 2;
    const ERROR_DEFAULT = 3;

    /**
     * @var string
     */
    protected $_default = '';

    /**
     * @var array
     */
    protected $_rules = array();

    /**
     * @var array
     */
    protected $_config = array(
        'error_mode' => self::ERROR_FATAL
    );

    /**
     * @param string $default
     * @param array  $ruleList
     */
    function __construct($default = '', array $ruleList)
    {
        $this->_rules   = $ruleList;
        $this->_default = $default;
    }

    /**
     * @param mixed  $data
     * @param string $forceRule
     * @return array
     * @throws Exception
     */
    public function parse($data = null, $forceRule = null)
    {
        $value = null;
        $rule  = null;

        if (is_array($data)) {
            $value = isset($data[0]) ? $data[0] : null;
            $rule  = isset($data[1]) ? $data[1] : null;
            return $this->parse($value, $rule);

        } else {
            $data = trim($data);
            $reg  = '#(.*)(' . implode('|', $this->_getCodeList()) . ')(.*)#iu';

            if (preg_match($reg, $data, $matches)) {
                $rule  = $matches[2];
                $value = 0;
                if (!empty($matches[1])) {
                    $value = $matches[1];
                } else if (!empty($matches[3])) {
                    $value = $matches[3];
                }
            }
        }

        if (is_null($value)) {
            $value = $data;
        }

        $value = $this->clean($value);
        $rule  = $this->checkRule($rule);

        if ($forceRule) {
            $rule = $forceRule;
        }

        if (empty($rule)) {
            $rule = $this->_default;
        }

        return array($value, $rule);
    }

    /**
     * @return array
     */
    protected function _getCodeList()
    {
        return array_keys($this->_rules);
    }

    /**
     * @param $value
     * @return float|string
     */
    public function clean($value)
    {
        $value = trim($value);

        $value = preg_replace("#[^0-9-+eE,.]#", '', $value);
        if (!preg_match('#\d[eE][-+]\d#', $value)) { // remove exponential format
            $value = str_replace(array('e', 'E'), '', $value);
        }

        $value = str_replace(',', '.', $value);
        $value = (float)$value;
        $value = round($value, Formatter::ROUND_DEFAULT);

        return $value;
    }

    /**
     * @param string $rule
     * @return bool|string
     * @throws Exception
     */
    public function checkRule($rule)
    {
        $rule = strtolower(trim($rule));

        if (empty($rule)) {
            return $this->_default;
        }

        if (isset($this->_rules[$rule])) {
            return $rule;
        }

        if ($rule) {

            if ($this->_config['error_mode'] == self::ERROR_FATAL) {
                throw new Exception('Undefined rule: ' . $rule);

            } else if ($this->_config['error_mode'] == self::ERROR_NOTICE) {
                trigger_error('Undefined currency: ' . $rule, E_USER_NOTICE);
            }

            return isset($this->_rules[$this->_default]) ? $this->_default : '';
        }

        return false;
    }

    /**
     * @param $newRule
     */
    public function addRule($newRule)
    {
        $this->_rules[$newRule] = $newRule;
    }

    /**
     * @param string $rule
     */
    public function removeRule($rule)
    {
        if (isset($this->_rules[$rule])) {
            unset($this->_rules[$rule]);
        }
    }
}
