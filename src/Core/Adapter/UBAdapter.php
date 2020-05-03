<?php


namespace App\Core\Adapter;


use App\Core\Interfaces\DocumentWritterInterface;
use App\Core\WardrobeEntity;
use DOMDocument;

class UBAdapter implements DocumentWritterInterface
{

    /**
     * @var DOMDocument|null
     */
    private $xmlDocument = null;

    private const MAPPING = [
        'UpperSkin' => 'shirt',
        'Pants' => 'pants',
        'Parachute' => 'hands',
        'Shoes' => 'shoes',
        'Accessories' => 'eyes',
        'UnderCoat' => 'accessories',
        'Armor' => 'tasks',
        'Decal' => 'decals',
        'Top' => 'shirtoverlay',
        'Hat' => 'hats',
        'Glasses' => 'glasses',
        'Ear' => 'ears',
        'Watch' => 'watches',
        'Mask' => 'beard',
    ];

    private const KEYWORD_UB = [
        'Hat' => 'prop',
        'Glasses' => 'prop',
        'Ear' => 'prop',
        'Watch' => 'prop',

    ];

    public function __construct()
    {
        $this->handleCreateXMLDocument();
    }

    public function handleCreateXMLDocument(): void
    {
        $this->xmlDocument = new DOMDocument('1.0', 'UTF-8');
    }

    /**
     * @param WardrobeEntity[] $objects
     * @return string
     */
    public function parse(array $objects): string
    {
        $xmlRoot = $this->xmlDocument->createElement("Peds");
        $xmlRoot = $this->xmlDocument->appendChild($xmlRoot);


        foreach ($objects as $object) {
            $pedElement = $this->xmlDocument->createElement('Ped', 'MP_M_FREEMODE_01');
            $this->handleAttributes($object, $pedElement);
            $xmlRoot->appendChild($pedElement);
        }
        return $this->xmlDocument->saveXML();
    }

    /**
     * @param WardrobeEntity $wardrobeEntity
     * @param \DOMElement $element
     */
    private function handleAttributes(WardrobeEntity $wardrobeEntity, \DOMElement $element): void
    {
        foreach (self::MAPPING as $iniName => $ubName) {
            $wardrobeValue = $wardrobeEntity->filterByName($iniName);
            if ($wardrobeValue !== null) {
                $keyWord = self::KEYWORD_UB[$iniName] ?? 'comp';
                $element->setAttribute($keyWord . '_' . $ubName, $wardrobeValue->getComponent());
                if ($wardrobeValue->isTextureDefined()) {
                    $element->setAttribute('tex_' . $ubName, $wardrobeValue->getTexture());
                }
            }
        }
    }


}