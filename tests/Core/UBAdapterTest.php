<?php


namespace Core;


use App\Core\Adapter\UBAdapter;
use App\Core\IniParser;
use App\Core\IO\File;
use App\Tests\DorianTestCase;
use PHPUnit\Runner\Exception;

class UBAdapterTest extends DorianTestCase
{


    private $fileTestName = null;

    public function setUp()
    {
        $this->fileTestName = dirname(__DIR__) . '/files/ini_wardrobe.ini';
    }

    public function testParseOk()
    {
        $expected = <<<EOD
<?xml version="1.0" encoding="UTF-8"?><Peds><Ped prop_hats="14" tex_hats="1" prop_glasses="16" tex_glasses="7" prop_ears="0" prop_watches="0" tex_watches="0" comp_beard="1" tex_beard="1" comp_shirt="1" tex_shirt="1" comp_pants="26" tex_pants="1" comp_hands="1" tex_hands="1" comp_shoes="26" tex_shoes="1" comp_eyes="8" tex_eyes="1" comp_accessories="38" tex_accessories="1" comp_tasks="2" tex_tasks="1" comp_decals="1" tex_decals="1" comp_shirtoverlay="191" tex_shirtoverlay="3" >MP_M_FREEMODE_01</Ped></Peds>
EOD;


        $file = new File($this->fileTestName);

        $testedInstance = new UBAdapter();
        $iniParser = new IniParser();

        $results = $iniParser->getWardrobeEntities($file);

        $ub = new UBAdapter();
        $xml = $ub->parse($results);


        $expectedDOM = new \DOMDocument();
        $expectedDOM->loadXML($expected);

        $actualDOM = new \DOMDocument();
        $actualDOM->loadXML($xml);


        $actualXPath = new \DOMXPath($actualDOM);
        $actualPed = $actualXPath->evaluate('//Ped')[0] ?? null;
        if (is_null($actualPed)) {
            $this->fail('Bad XML');
        }

        $expectedXPath = $expectedXPath = new \DOMXPath($expectedDOM);
        $expectedPed = $expectedXPath->evaluate('//Ped')[0] ?? null;
        if (is_null($expectedPed)) {
            throw new Exception("Bad XML expected...");
        }
        $this->assertAttributes($expectedPed, $actualPed);


    }

    private function assertAttributes(\DOMElement $expected, \DOMElement $actual)
    {
        foreach ($expected->attributes as $attribute) {
            $this->assertAttribute($attribute, $actual);
        }
    }

    private function assertAttribute(\DOMAttr $expected, \DOMElement $actual)
    {
        $attr = $actual->getAttributeNode($expected->name);
        if ($attr === false) {
            $this->fail('Bad assertion missing attr : ' . $expected->name);
        }
        $this->assertEquals($expected->value, $attr->value);
    }

}