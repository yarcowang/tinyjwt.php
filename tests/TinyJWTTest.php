<?php
/**
 * Proj. tinyjwt.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 17/1/2 下午6:30
 */

class TinyJWTTest extends \PHPUnit\Framework\TestCase
{
    public function testGetTokenParagonIE()
    {
        $t = new \Yarco\TinyJWT\TinyJWT();
        $token = $t->newKey()->getToken(['name' => 'Yarco']);
        $this->assertStringStartsWith(base64_encode(json_encode(['name' => 'Yarco'])), $token);
    }

    public function testGetTokenOpenSSL()
    {
        $t = new \Yarco\TinyJWT\TinyJWT(new \Yarco\TinyJWT\Adapter\OpenSSLDriver());
        $token = $t->newKey()->getToken(['name' => 'Yarco']);
        $this->assertStringStartsWith(base64_encode(json_encode(['name' => 'Yarco'])), $token);
    }

    public function testVerifyTokenParagonIE()
    {
        $t = new \Yarco\TinyJWT\TinyJWT();
        $token = $t->newKey()->getToken(['name' => 'Yarco']);
        $this->assertEquals(['name' => 'Yarco'], $t->verify($token));
        $this->assertNotEquals(['name' => 'yarco'], $t->verify($token));
    }

    public function testVerifyTokenOpenSSL()
    {
        $t = new Yarco\TinyJWT\TinyJWT(new \Yarco\TinyJWT\Adapter\OpenSSLDriver());
        $token = $t->newKey()->getToken(['name' => 'Yarco']);
        $this->assertEquals(['name' => 'Yarco'], $t->verify($token));
        $this->assertNotEquals(['name' => 'yarco'], $t->verify($token));
    }
}
