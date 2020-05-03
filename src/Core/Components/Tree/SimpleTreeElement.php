<?php


namespace App\Core\Components\Tree;


use App\Core\Utility;

class SimpleTreeElement implements TreeElementInterface
{

    /**
     * @var string
     */
    private $routename;
    /**
     * @var string
     */
    private $name;


    /**
     * @var string[]
     */
    private $routeParameters = [];
    /**
     * @var bool
     */
    private $forceActive;

    /**
     * TreeElement constructor.
     * @param string $routename
     * @param string $name
     * @param string[] $routeParameters
     */
    public function __construct(string $name, string $routename, array $routeParameters, $forceActive = false)
    {
        $this->routename = $routename;
        $this->name = $name;
        $this->routeParameters = $routeParameters;
        $this->forceActive = $forceActive;
    }


    public function setIsActiveForced(bool $state): void
    {
        $this->forceActive = $state;
    }


    public function isActiveForced(): bool
    {
        return $this->forceActive;
    }


    /**
     * @return string
     */
    public function getRoutename(): string
    {
        return $this->routename;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }


}