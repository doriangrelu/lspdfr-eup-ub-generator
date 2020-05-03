<?php


namespace App\Core\Components;


use App\Core\Components\Tree\ArrayTreeElement;
use App\Core\Components\Tree\SimpleTreeElement;
use App\Core\Components\Tree\TreeElementInterface;
use App\Core\Exceptions\Components\Tree\BadArrayTreeElementsException;
use App\Core\Utility;

class BreadcrumbsTree
{
    private $elements = [];

    private $mandatoryParameters = [];


    private static $instance = null;

    /**
     * BreadcrumbsTree constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (self::$instance !== null) {
            throw new \Exception("Please not call constructor directly, please use getInstance method");
        }

    }

    /**
     * Singleton instance
     *
     * @return BreadcrumbsTree|null
     * @throws \Exception
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new BreadcrumbsTree();
        }
        return self::$instance;
    }

    /**
     * Add auto mandatory params
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function addMandatory($name, $value): self
    {
        $this->mandatoryParameters[$name] = $value;
        return $this;
    }


    /**
     * Create simple breadcrumbs element
     *
     * @param string $name
     * @param string $routename
     * @param array $routeParameter
     * @param bool $autoMandatory
     * @return BreadcrumbsTree
     * @throws BadArrayTreeElementsException
     */
    public function addSimpleElement(string $name, string $routename, array $routeParameter = [], bool $autoMandatory = true): self
    {
        $matchElements = $this->findElementByName($name);
        if (count($matchElements) > 0) {
            $this->replaceElement($matchElements, $routename, $this->_makeMandatory($routeParameter, $autoMandatory));
        } else {
            $this->elements[] = new SimpleTreeElement($name, $routename, $this->_makeMandatory($routeParameter, $autoMandatory));
        }
        return $this;
    }

    /**
     * Rename element from actual element name if exists.
     * If not exists nothing action.
     *
     * @param string $actualName
     * @param string $newName
     * @return $this
     */
    public function renameElemment(string $actualName, string $newName): self
    {
        $matchElements = $this->findElementByName($actualName);
        foreach ($matchElements as $element) {
            Utility::modifyPropertyValue($element, 'name', $newName);
        }
        return $this;
    }

    /**
     * Find elements by name
     *
     * @param string $name
     * @return array
     */
    public function findElementByName(string $name): array
    {
        return array_filter($this->elements, function (TreeElementInterface $element) use ($name) {
            if ($element instanceof SimpleTreeElement) {
                return $element->getName() === $name;
            }
            return false;
        });
    }

    /**
     * Replace element parameters with reflection method.
     *
     * @param SimpleTreeElement[] $elements
     * @param string $routeName
     * @param array $routeParameter
     */
    private function replaceElement(array $elements, string $routeName, array $routeParameter): void
    {
        foreach ($elements as $element) {
            Utility::modifyPropertyValue($element, 'routename', $routeName);
            Utility::modifyPropertyValue($element, 'routeParameters', $routeParameter);
        }
    }

    /**
     * Create dropdown breadcrumbs element from array description
     *
     * @param string $currentElementName
     * @param string $currentElementRouteName
     * @param array $currentRouteParameters
     * @param array $elements
     * @param bool $autoMandatory
     * @return BreadcrumbsTree
     * @throws BadArrayTreeElementsException
     */
    public function addArrayElement(string $currentElementName, string $currentElementRouteName, array $currentRouteParameters, array $elements, bool $autoMandatory = true): self
    {
        $arrayElement = new ArrayTreeElement($currentElementName, $currentElementRouteName, $this->_makeMandatory($currentRouteParameters, $autoMandatory));
        foreach ($elements as $element) {
            if ($this->_arrayCorrectlyFormated($element) === false) {
                throw new BadArrayTreeElementsException();
            }
            $arrayElement->addElement($element['name'], $element['routeName'], $this->_makeMandatory($element['routeParameters'], $autoMandatory));
        }

        $this->elements[] = $arrayElement;
        return $this;
    }


    /**
     *
     * @return TreeElementInterface[]
     */
    public function getElements(): iterable
    {
        return $this->elements;
    }

    /**
     * Is last element
     *
     * @param TreeElementInterface $element
     * @return bool
     */
    public function isLastElement(TreeElementInterface $element): bool
    {
        $isNoEmpty = count($this->elements) > 0;
        if ($isNoEmpty === false) {
            return false;
        }

        $lastElement = $this->elements[count($this->elements) - 1];
        return $element === $lastElement;
    }

    /**
     * @param array $elements
     * @return bool
     */
    private function _arrayCorrectlyFormated(array &$elements): bool
    {

        if (isset($elements['routeParameters']) === false) {
            $elements['routeParameters'] = [];
        }

        return isset($elements['name']) && isset($elements['routeName']);
    }

    /**
     * @param array $routeParameters
     * @return array
     * @throws BadArrayTreeElementsException
     */
    private function _makeMandatory(array $routeParameters, bool $autoMandatory): array
    {
        if ($autoMandatory === false) {
            return $routeParameters;
        }
        $final = [];
        foreach ($routeParameters as $name => $value) {
            if (is_int($name)) {
                if (!isset($this->mandatoryParameters[$value])) {
                    throw new BadArrayTreeElementsException("Missing mandatory params $name, please addMandatory.");
                }
                $final[$value] = $this->mandatoryParameters[$value];
            } else {
                $final[$name] = $value;
            }
        }

        return $final;
    }


}