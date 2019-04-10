<?php
namespace Mastercard\Developer\OAuth\Utils;

use PHPUnit\Framework\TestCase;

class AuthenticationUtilsTest extends TestCase {

    public function testLoadSigningKey_ShouldReturnKey() {

        // GIVEN
        $keyContainerPath = './resources/test_key_container.p12';
        $keyAlias = 'mykeyalias';
        $keyPassword = 'Password1';

        // WHEN
        $privateKey = AuthenticationUtils::loadSigningKey($keyContainerPath, $keyAlias, $keyPassword);

        // THEN
        $this->assertNotNull($privateKey);
    }
}
