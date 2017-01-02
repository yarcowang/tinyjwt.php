<?php
/**
 * Proj. tinyjwt.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 17/1/2 下午12:39
 */

namespace Yarco\TinyJWT;

use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\SignatureKeyPair;
use ParagonIE\Halite\Symmetric\AuthenticationKey;
use ParagonIE\Halite\Symmetric\Crypto as SymmetricCrypto;
use ParagonIE\Halite\Asymmetric\Crypto as AsymmetricCrypto;

class TinyJWT
{
    const SYMMETRIC = 1;
    const ASYMMETRIC = 2;

    protected $_type;

    /**
     * @var AuthenticationKey|SignatureKeyPair
     */
    protected $_key;

    public function __construct($auto = true)
    {
        if ($auto) {
            $this->setUp();
        }
    }

    public function setUp($type = self::SYMMETRIC, $secret = '')
    {
        if ($type === self::SYMMETRIC || $type === self::ASYMMETRIC) {
            $this->_type = $type;
            $this->_key = $type === self::SYMMETRIC ?
                KeyFactory::generateAuthenticationKey($secret) : KeyFactory::generateSignatureKeyPair($secret)
            ;
        } else if ($type instanceof AuthenticationKey) {
            $this->_type = self::SYMMETRIC;
            $this->_key = $type;
        } else if ($type instanceof SignatureKeyPair) {
            $this->_type = self::ASYMMETRIC;
            $this->_key = $type;
        } else { // default to Symmetric
            $this->_type = self::SYMMETRIC;
            $this->_key = KeyFactory::generateAuthenticationKey($secret);
        }
    }

    public function setUpFromFile($file, $type = self::SYMMETRIC)
    {
        $this->_type = $type;
        $this->_key = $this->_type === self::SYMMETRIC ?
            KeyFactory::loadAuthenticationKey($file) : KeyFactory::loadSignatureKeyPair($file)
        ;
    }

    public function saveTo($file)
    {
        return KeyFactory::save($this->_key, $file);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return AuthenticationKey|SignatureKeyPair
     */
    public function getKey()
    {
        return $this->_key;
    }

    public function getToken(array $payload = [])
    {
        $s1 = base64_encode(json_encode($payload, JSON_FORCE_OBJECT));
        $func = $this->_type === self::SYMMETRIC ?
            [SymmetricCrypto::class, 'authenticate'] : [AsymmetricCrypto::class, 'sign']
        ;
        $args = $this->_type === self::SYMMETRIC ?
            [$s1, $this->_key] : [$s1, $this->_key->getSecretKey()];
        $s2 = base64_encode(call_user_func_array($func, $args));
        return $s1 . '.' . $s2;
    }

    public function verify($token)
    {
        list($s1, $s2) = explode('.', $token);
        $s2 = base64_decode($s2);

        $func = $this->_type === self::SYMMETRIC ?
            [SymmetricCrypto::class, 'verify'] : [AsymmetricCrypto::class, 'verify']
        ;
        $args = $this->_type === self::SYMMETRIC ?
            [$s1, $this->_key, $s2] : [$s1, $this->_key->getPublicKey(), $s2]
        ;

        $ret = call_user_func_array($func, $args);
        if (!$ret) {
            return false;
        }

        return json_decode(base64_decode($s1), JSON_OBJECT_AS_ARRAY);
    }
}