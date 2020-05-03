<?php


namespace App\Core\Behavior;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ApiPaginator
 * @package App\Core\Behavior
 */
class QueryPaginator
{

    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    private $request;

    /**
     * ApiPaginator constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }


    /**
     * @param QueryBuilder $queryBuilder
     * @param int $maxPerPage
     * @param string $pageQueryName
     * @return PaginatedCollection
     */
    public function paginate(QueryBuilder $queryBuilder, int $maxPerPage = 10, string $pageQueryName = 'page'): PaginatedCollection
    {
        $currentPage = $this->request->query->getInt($pageQueryName, 1);

        $pagerAdapter = new DoctrineORMAdapter($queryBuilder);

        $paginator = new Pagerfanta($pagerAdapter);
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($currentPage);

        return new PaginatedCollection($paginator->getCurrentPageResults(), $currentPage, $paginator->getNbPages(), $paginator->getNbResults());
    }

}