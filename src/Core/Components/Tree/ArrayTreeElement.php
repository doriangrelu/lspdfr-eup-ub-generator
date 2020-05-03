<?php


namespace App\Core\Components\Tree;


class ArrayTreeElement implements TreeElementInterface
{

    private $currentName;


    private $elements = [];
    /**
     * @var string
     */
    private $routeName;
    /**
     * @var array
     */
    private $routeParameters;

    public function __construct(string $currentElementName, string $routeName, array $routeParameters = [])
    {
        $this->currentName = $currentElementName;
        $this->routeName = $routeName;
        $this->routeParameters = $routeParameters;
    }

    public function addElement(string $name, string $routename, array $parameter = []): self
    {
        if ($routename === $this->routeName && $this->routeParameters == $parameter) {
            return $this;
        }

        $this->elements[] = new SimpleTreeElement($name, $routename, $parameter);
        return $this;
    }

    public function createSimpleCurrentElement(): SimpleTreeElement
    {
        return new SimpleTreeElement($this->currentName, $this->routeName, $this->routeParameters);
    }

    public function isEmpty(): bool
    {
        return count($this->elements) === 0;
    }

    /**
     * @return mixed
     */
    public function getCurrentName()
    {
        return $this->currentName;
    }

    /**
     * @return string
     */
    public function getRouteName(): string
    {
        return $this->routeName;
    }

    /**
     * @return array
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }


    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }


}