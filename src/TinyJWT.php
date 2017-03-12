<?php
/**
 * Proj. tinyjwt.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 17/1/2 下午12:39
 */

namespace Yarco\TinyJWT;

use Yarco\TinyJWT\Adapter\IDriver;
use Yarco\TinyJWT\Adapter\OpenSSLDriver;
use Yarco\TinyJWT\Adapter\ParagonIEHaliteDriver;

class TinyJWT
{
    /**
     * @var IDriver
     */
    private $_driver;

    /**
     * TinyJWT constructor.
     * @param IDriver|null $driver
     */
    public function __construct(IDriver $driver = null)
    {
        if (!$driver) {
            $driver = extension_loaded('libsodium') ?
                new ParagonIEHaliteDriver() :
                new OpenSSLDriver()
                ;
        }
        $this->_driver = $driver;
    }

    public function newKey()
    {
        $this->_driver->newKey();
        return $this;
    }

    public function saveTo(string $file)
    {
        return $this->_driver->saveTo($file);
    }

    public function loadFrom(string $file)
    {
        return $this->_driver->loadFrom($file);
    }

    public function getToken(array $payload = []) : string
    {
        $s1 = base64_encode(json_encode($payload, JSON_FORCE_OBJECT));
        return $s1 . '.' . base64_encode($this->_driver->sign($s1));
    }

    public function verify($token)
    {
        list($s1, $s2) = explode('.', $token);
        $s2 = base64_decode($s2);

        $ret = $this->_driver->verify($s1, $s2);
        if (!$ret) {
            return false;
        }

        return json_decode(base64_decode($s1), JSON_OBJECT_AS_ARRAY);
    }
}