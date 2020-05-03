<?php


namespace App\Tests\Core;

use App\Core\IniParser;
use App\Core\IO\File;
use App\Tests\DorianTestCase;

class IniParserTest extends DorianTestCase
{

    private $fileTestName = null;

    public function setUp()
    {
        $this->fileTestName = dirname(__DIR__) . '/files/ini_test.ini';
    }

    public function lookUpTestedObject(array $constructorArgs = [], array $mockedMethods = []): IniParser
    {
        return $this->getPartialMock(IniParser::class, $constructorArgs, $mockedMethods);
    }


    public function testParseWardrobeOk()
    {
        $file = new File($this->fileTestName);
        $this->assertReadFile($file);
        $testedInstance = $this->lookUpTestedObject();
        $results = $testedInstance->getWardrobeEntities($file);
        $this->assertCount(1, $results);
        $entity = $results[0];

        $this->assertEquals(2, $entity->filterByName('Foo')->getComponent());
        $this->assertEquals(1, $entity->filterByName('Foo')->getTexture());


        $this->assertEquals(1, $entity->filterByName('Bar')->getComponent());
        $this->assertEquals(2, $entity->filterByName('Bar')->getTexture());

        $this->assertEquals('Male', $entity->filterByName('Gender')->getComponent());
    }


    private function assertReadFile(File $file)
    {
        $this->assertEquals([
            'A' => [
                'Gender' => 'Male',
                'Foo' => '2:1',
                'Bar' => '1:2'
            ],
        ],
            $file->parseIni(true)
        );
    }
}