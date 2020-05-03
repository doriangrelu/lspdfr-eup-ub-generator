<?php


namespace App\Tests;


use PHPUnit\Framework\TestCase;

class DorianTestCase extends TestCase
{
    /**
     * @param string $classname
     * @param array $constructorArguments
     * @param array $mockedMethods
     * @return \PHPUnit\Framework\MockObject\MockObject
     * @throws \ReflectionException
     */
    protected function getPartialMock(string $classname, array $constructorArguments = [], array $mockedMethods = []): \PHPUnit\Framework\MockObject\MockObject
    {
        return $this->getMockForAbstractClass($classname, $constructorArguments, '', true, true, true, $mockedMethods);
    }

}