<?php


namespace App\Core\Interfaces;


use App\Core\Exceptions\Security\SSLException;

interface EncryptionInterface
{
    /**
     * @param string $string
     * @param string $key
     * @return string
     */
    public function encrypt(string $string, string $key): string;

    /**
     * @param string $string
     * @param string $key
     * @return string
     * @throws SSLException
     */
    public function decrypt(string $string, string $key): string;
}