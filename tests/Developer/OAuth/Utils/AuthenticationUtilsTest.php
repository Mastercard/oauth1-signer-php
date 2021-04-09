<?php
namespace Mastercard\Developer\OAuth\Utils;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

class AuthenticationUtilsTest extends TestCase {

    public function testConstruct_ShouldBePrivate() {
        // GIVEN
        $class = new ReflectionClass('Mastercard\Developer\OAuth\Utils\AuthenticationUtils');
        $constructor = $class->getConstructor();

        // WHEN
        $isPrivate = $constructor->isPrivate();

        // THEN
        $this->assertTrue($isPrivate);

        // COVERAGE
        $constructor->setAccessible(true);
        $constructor->invoke($class->newInstanceWithoutConstructor());
    }

    public function testLoadSigningKey_ShouldReturnKey() {

        // GIVEN
        $keyContainerPath = './resources/test_key_container.p12';
        $keyAlias = 'mykeyalias';
        $keyPassword = 'Password1';

        // WHEN
        $privateKey = AuthenticationUtils::loadSigningKey($keyContainerPath, $keyAlias, $keyPassword);

        // THEN
        $this->assertNotEmpty($privateKey);
    }

    public function testLoadSigningKey_ShouldThrowInvalidArgumentException_WhenWrongPassword() {

        // THEN
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Failed to open keystore with the provided password!');

        // GIVEN
        $keyContainerPath = './resources/test_key_container.p12';
        $keyAlias = 'mykeyalias';
        $keyPassword = 'Wrong password';

        // WHEN
        AuthenticationUtils::loadSigningKey($keyContainerPath, $keyAlias, $keyPassword);
    }

    public function testLoadSigningKey_ShouldThrowInvalidArgumentException_WhenFileDoesNotExists() {

        // THEN
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Failed to read the given file: ./resources/some file');

        // GIVEN
        $keyContainerPath = './resources/some file';
        $keyAlias = 'mykeyalias';
        $keyPassword = 'Password1';

        // WHEN
        AuthenticationUtils::loadSigningKey($keyContainerPath, $keyAlias, $keyPassword);
    }
}
