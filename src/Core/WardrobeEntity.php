<?php


namespace App\Core;


class WardrobeEntity
{
    /**
     * @var null
     */
    private $name = null;

    /**
     * @var WardrobeValues[]
     */
    private $wardrobe = [];

    public function __construct(string $name, array $wardrobe)
    {
        $this->name = $name;
        foreach ($wardrobe as $itemName => $values) {
            $this->wardrobe[$this->sanitizeString($itemName)] = IniParser::parseValuesLexem($values);
        }
    }

    private function sanitizeString(string $value): string
    {
        return trim(preg_replace('/\s+/', '', $value));
    }

    /**
     * @return null
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return WardrobeValues[]
     */
    public function getWardrobe(): array
    {
        return $this->wardrobe;
    }

    public function filterByName(string $name): ?WardrobeValues
    {
        return $this->wardrobe[$name] ?? null;
    }

    public function isTextureDefined(string $name): bool
    {
        $element = $this->filterByName($name);
        if ($element === null) {
            return false;
        }
        return $element->isTextureDefined();
    }

}