<?php


namespace App\Core;


use App\Core\IO\File;

class IniParser
{

    private const LEXER_GRAMMAR = '/[0-9]+\:[0-9]+/';

    const INDEX_WN = 0;
    const INDEX_AUTO = 1;

    /**
     * @param File $iniFile
     * @param int $indexMethod
     * @return WardrobeEntity[]
     * @throws Exceptions\IO\FileNotFoundException
     */
    public function getWardrobeEntities(File $iniFile, int $indexMethod = self::INDEX_AUTO): iterable
    {
        $entities = [];
        $iniArray = $iniFile->parseIni(true);
        foreach ($iniArray as $itemName => $wardrobe) {
            if ($indexMethod === self::INDEX_AUTO) {
                $entities[] = new WardrobeEntity($itemName, $wardrobe);
            } else {
                $entities[$itemName] = new WardrobeEntity($itemName, $wardrobe);
            }
        }
        return $entities;
    }

    public static function parseValuesLexem(string $lexemValues): WardrobeValues
    {
        $component = $lexemValues;
        $texture = null;
        if (preg_match(self::LEXER_GRAMMAR, $lexemValues) != false) {
            list($component, $texture) = explode(':', $lexemValues);
        }
        return new WardrobeValues($component, $texture);
    }

}