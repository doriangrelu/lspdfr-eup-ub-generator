<?php


namespace App\Core\Components;


use Symfony\Component\HttpFoundation\Request;

class Navigation
{

    private $selectedItems = [];

    private static $instance = null;


    public function __construct()
    {
        if (self::$instance !== null) {
            throw new \Exception("Please not call directly constructor, use getInstance method");
        }
    }


    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new Navigation();
        }

        return self::$instance;
    }


    public function add(string $name): self
    {
        $this->selectedItems[] = $name;
        return $this;
    }

    public function isSelectedItem(string $name, ?Request $request = null): bool
    {
        if ($request !== null && $request->get('_route') === $name) {
            return true;
        }
        $flippedElements = array_flip($this->selectedItems);
        return isset($flippedElements[$name]);
    }

}