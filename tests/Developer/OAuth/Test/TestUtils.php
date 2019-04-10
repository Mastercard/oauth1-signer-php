<?php

namespace Mastercard\Developer\OAuth\Test;

use Mastercard\Developer\OAuth\Utils\SecurityUtils;

class TestUtils
{
    public static function callStatic($className, $functionName, $args) {
        if (is_string($args) || is_null($args)) {
            $args = array($args);
        }
        $class = new \ReflectionClass('Mastercard\Developer\OAuth' . $className);
        $method = $class->getMethod($functionName);
        $method->setAccessible(true);
        return $method->invokeArgs(null, $args);
    }

    public static function getTestPrivateKey() {
        return SecurityUtils::loadPrivateKey('./resources/test_key_container.p12','mykeyalias', 'Password1');
    }
}