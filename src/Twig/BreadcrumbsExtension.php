<?php

namespace App\Twig;

use App\Core\Components\BreadcrumbsTree;
use App\Core\Components\Tree\ArrayTreeElement;
use App\Core\Components\Tree\TreeElementInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class BreadcrumbsExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $twig;


    /**
     * BreadcrumbsExtension constructor.
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('display_breadcrumbs', [$this, 'display'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('display_breadcrumbs_element', [$this, 'displayElement'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('is_last_breadcrumbs_elemens', [$this, 'isLastElement'], [
                'is_safe' => ['html']
            ]),
        ];
    }

    /**
     * @param TreeElementInterface $element
     * @return bool
     * @throws \Exception
     */
    public function isLastElement(TreeElementInterface $element): bool
    {
        return BreadcrumbsTree::getInstance()->isLastElement($element);
    }

    /**
     * @param TreeElementInterface $treeElement
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function displayElement(TreeElementInterface $treeElement): string
    {
        $template = '_breadcrumbs_simple_element';
        if ($treeElement instanceof ArrayTreeElement) {
            if ($treeElement->isEmpty()) {
                $isForced = false;
                if ($this->isLastElement($treeElement)) {
                    $isForced = true;
                }
                $treeElement = $treeElement->createSimpleCurrentElement();
                $treeElement->setIsActiveForced($isForced);
            } else {
                $template = '_breadcrumbs_array_element';
            }
        }

        return $this->twig->render('_elements/_partials/' . $template . '.html.twig', [
            'element' => $treeElement,
        ]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function display()
    {
        return $this->twig->render('_elements/_breadcrumbs.html.twig', [
            'tree' => BreadcrumbsTree::getInstance()->getElements(),
        ]);
    }
}
