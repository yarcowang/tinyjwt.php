<?php
/**
 * Proj. tinyjwt.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 3/11/17 11:56 PM
 */

namespace Yarco\TinyJWT\Adapter;


class OpenSSLDriver extends ADriver
{
    /**
     * @var array
     */
    private $_config;

    /**
     * OpenSSLDriver constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->_config = $config ?: ['digest_alg' => 'sha512', 'private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA];
    }

    /**
     * {@inheritdoc}
     */
    public function newKey(): ADriver
    {
        $this->setKey(openssl_pkey_new($this->_config));
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function saveTo(string $file): bool
    {
        return openssl_pkey_export_to_file($this->getKey(), $file, null, $this->_config);
    }

    /**
     * {@inheritdoc}
     */
    public function loadFrom(string $file): bool
    {
        $ret = openssl_pkey_get_private('file://' . $file);
        if ($ret === false) {
            $this->_error = openssl_error_string();
            return false;
        }
        $this->setKey($ret);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicKey()
    {
        return openssl_pkey_get_details($this->getKey())['key'];
    }

    /**
     * {@inheritdoc}
     */
    public function getSecretKey()
    {
        return openssl_pkey_get_private($this->getKey());
    }

    /**
     * {@inheritdoc}
     */
    public function sign(string $data)
    {
        $ret = openssl_sign($data, $sign, $this->getSecretKey());
        if (!$ret) {
            $this->_error = openssl_error_string();
            return false;
        }

        return $sign;
    }

    /**
     * {@inheritdoc}
     */
    public function verify(string $data, string $sign): bool
    {
        $ret = openssl_verify($data, $sign, $this->getPublicKey());
        if ($ret === -1) {
            $this->_error = openssl_error_string();
            return false;
        }
        return (bool)$ret;
    }
}