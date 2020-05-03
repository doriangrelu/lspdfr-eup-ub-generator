<?php


namespace App\Core;


class WardrobeValues
{

    private $component = null;


    private $texture = null;

    public function __construct(string $component, ?string $texture = null)
    {
        $this->component = $component;
        $this->texture = $texture;
    }

    /**
     * @return string|null
     */
    public function getComponent(): string
    {
        return $this->component;
    }

    /**
     * @return string|null
     */
    public function getTexture(): ?string
    {
        return $this->texture;
    }

    public function isTextureDefined()
    {
        return $this->texture !== null;
    }

}