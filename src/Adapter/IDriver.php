<?php
/**
 * Proj. tinyjwt.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 3/11/17 8:41 PM
 */

namespace Yarco\TinyJWT\Adapter;


interface IDriver
{
    /**
     * Generate new key-pair
     *
     * @return ADriver
     */
    public function newKey() : ADriver;

    /**
     * Save private key to external file
     *
     * @param string $file
     * @return bool
     */
    public function saveTo(string $file) : bool;

    /**
     * Load private key from external file
     *
     * @param string $file
     * @return bool
     */
    public function loadFrom(string $file) : bool;

    /**
     * Get public key
     *
     * @return mixed
     */
    public function getPublicKey();

    /**
     * Get secret key
     *
     * @return mixed
     */
    public function getSecretKey();

    /**
     * @param string $data
     * @return mixed
     */
    public function sign(string $data);

    /**
     * @param string $data
     * @param string $sign
     * @return bool
     */
    public function verify(string $data, string $sign) : bool;
}