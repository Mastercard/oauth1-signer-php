<?php
namespace Mastercard\Developer\OAuth\Utils;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @deprecated
 */
class SecurityUtilsTest extends TestCase {

    public function testConstruct_ShouldBePrivate() {
        // GIVEN
        $class = new ReflectionClass('Mastercard\Developer\OAuth\Utils\SecurityUtils');
        $constructor = $class->getConstructor();

        // WHEN
        $isPrivate = $constructor->isPrivate();

        // THEN
        $this->assertTrue($isPrivate);

        // COVERAGE
        $constructor->setAccessible(true);
        $constructor->invoke($class->newInstanceWithoutConstructor());
    }

    public function testLoadPrivateKey_ShouldReturnKey() {

        // GIVEN
        $keyContainerPath = './resources/test_key_container.p12';
        $keyAlias = 'mykeyalias';
        $keyPassword = 'Password1';

        // WHEN
        $privateKey = SecurityUtils::loadPrivateKey($keyContainerPath, $keyAlias, $keyPassword);

        // THEN
        $this->assertNotEmpty($privateKey);
    }
}
