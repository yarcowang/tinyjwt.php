<?php

/**
 * Proj. tinyjwt.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 3/12/17 1:23 PM
 */

class OpenSSLDriverTest extends \PHPUnit\Framework\TestCase
{
    public function testVerifyData()
    {
        $obj = new \Yarco\TinyJWT\Adapter\OpenSSLDriver;
        $sign = $obj->newKey()->sign('test');
        $this->assertTrue($obj->verify('test', $sign));
    }

    public function testSavedKey()
    {
        $obj = new \Yarco\TinyJWT\Adapter\OpenSSLDriver;
        $sign = $obj->newKey()->sign('test');

        $key = __DIR__ . '/fixtures/openssl_key';
        $obj->saveTo($key);
        $obj->loadFrom($key);
        $this->assertTrue($obj->verify('test', $sign));
    }
}
