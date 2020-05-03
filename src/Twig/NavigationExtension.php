<?php

namespace App\Twig;

use App\Core\Components\Navigation;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{

    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_selected_nav_item', [$this, 'isSelected']),
        ];
    }

    public function isSelected(string $name, string $keyword = 'active', bool $withClassAttr = false): string
    {
        if (Navigation::getInstance()->isSelectedItem($name, $this->request)) {
            return $withClassAttr ? "class='$keyword'" : $keyword;
        }
        return '';
    }
}
