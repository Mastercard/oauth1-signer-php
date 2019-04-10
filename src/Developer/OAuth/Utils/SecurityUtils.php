<?php
namespace Mastercard\Developer\OAuth\Utils;

/**
 * Utility class.
 * @package Mastercard\Developer\OAuth1Signer\Utils
 */
class SecurityUtils {

    /**
     * Load a RSA key out of a PKCS#12 container.
     * @throws \InvalidArgumentException
     */
    public static function loadPrivateKey($pkcs12KeyFilePath, $signingKeyAlias, $signingKeyPassword) { //NOSONAR
        if (!$keystore = file_get_contents($pkcs12KeyFilePath)) {
            throw new \InvalidArgumentException('Error: Unable to read the keystore file in ' . $pkcs12KeyFilePath);
        }

        openssl_pkcs12_read($keystore, $certs, $signingKeyPassword);
        if (is_null($certs)) {
            throw new \InvalidArgumentException('Unable open keystore with provided password');
        }

        return openssl_get_privatekey($certs['pkey']);
    }
}
