<?php
/**
 * Proj. tinyjwt.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 3/11/17 11:30 PM
 */

namespace Yarco\TinyJWT\Adapter;

/**
 * Class ADriver
 * @package Yarco\TinyJWT\Adapter
 */
abstract class ADriver implements IDriver
{
    /**
     * @var mixed $_key
     */
    protected $_key;

    /**
     * @var string $_error
     */
    protected $_error;

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->_key = $key;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->_error;
    }
}