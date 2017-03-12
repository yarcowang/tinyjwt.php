<?php
/**
 * Proj. tinyjwt.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 3/11/17 8:44 PM
 */

namespace Yarco\TinyJWT\Adapter;


use ParagonIE\Halite\Asymmetric\Crypto;
use ParagonIE\Halite\KeyFactory;

class ParagonIEHaliteDriver extends ADriver
{
    /**
     * {@inheritdoc}
     */
    public function newKey(): ADriver
    {
        $this->setKey(KeyFactory::generateSignatureKeyPair());
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function saveTo(string $file): bool
    {
        return KeyFactory::save($this->getKey(), $file);
    }

    /**
     * {@inheritdoc}
     */
    public function loadFrom(string $file): bool
    {
        try {
            $this->setKey(KeyFactory::loadSignatureKeyPair($file));
            return true;
        } catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicKey()
    {
        return $this->getKey()->getPublicKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getSecretKey()
    {
        return $this->getKey()->getSecretKey();
    }

    /**
     * {@inheritdoc}
     */
    public function sign(string $data)
    {
        return Crypto::sign($data, $this->getSecretKey());
    }

    /**
     * {@inheritdoc}
     */
    public function verify(string $data, string $sign): bool
    {
        try {
            $ret = Crypto::verify($data, $this->getPublicKey(), $sign);
            return $ret;
        } catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
    }
}