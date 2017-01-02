<?php
/**
 * Proj. tinyjwt.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 17/1/2 下午6:30
 */

class TinyJWTTest extends PHPUnit_Framework_TestCase
{
    public function testAutoSetup()
    {
        $t = new \Yarco\TinyJWT\TinyJWT();
        $this->assertTrue($t->getType() === Yarco\TinyJWT\TinyJWT::SYMMETRIC);
        $this->assertInstanceOf(\ParagonIE\Halite\Symmetric\AuthenticationKey::class, $t->getKey());
    }

    public function testCustomSetup()
    {
        $t1 = new \Yarco\TinyJWT\TinyJWT(false);
        $t1->setUp(\Yarco\TinyJWT\TinyJWT::ASYMMETRIC, '1111');
        $t1->saveTo(__DIR__ . '/fixtures/test.key');

        $t2 = new \Yarco\TinyJWT\TinyJWT(false);
        $t2->setUpFromFile(__DIR__ . '/fixtures/test.key', \Yarco\TinyJWT\TinyJWT::ASYMMETRIC);
        $this->assertEquals($t1, $t2);

        unlink(__DIR__ . '/fixtures/test.key');
    }

    public function testGetToken()
    {
        $t = new \Yarco\TinyJWT\TinyJWT();
        $token = $t->getToken(['name' => 'Yarco']);
        $this->assertStringStartsWith(base64_encode(json_encode(['name' => 'Yarco'])), $token);
    }

    public function testVerifyToken()
    {
        $t = new \Yarco\TinyJWT\TinyJWT();
        $token = $t->getToken(['name' => 'Yarco']);
        $this->assertEquals(['name' => 'Yarco'], $t->verify($token));
        $this->assertNotEquals(['name' => 'yarco'], $t->verify($token));

        $t2 = new Yarco\TinyJWT\TinyJWT(false);
        $t2->setUp(\Yarco\TinyJWT\TinyJWT::ASYMMETRIC);
        $token2 = $t2->getToken(['name' => 'Yarco']);
        $this->assertFalse($t->verify($token2));
    }
}
