<?php


namespace App\Core\Behavior\ORM;


use App\Core\Utility;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;

trait FiltersTrait
{

    private function _search(QueryBuilder $qb, array $fields, ?string $keywords, bool $advanced = true, string $whereLexem = 'andWhere'): void
    {
        if (is_null($keywords)) {
            return;
        }
        $cleaned = trim($keywords);
        if (empty($cleaned)) {
            return;
        }

        $searcher = [];
        $exploded = explode(' ', $cleaned);
        foreach ($fields as $field) {
            $fieldAlias = Utility::hash($field);
            $searcher[] = "$field LIKE :param$fieldAlias";
            $searcher[] = "$field LIKE :percentParam$fieldAlias";
            $searcher[] = "$field=:param$fieldAlias";

            if ($advanced) {
                foreach ($exploded as $param) {
                    $qb->setParameter('param' . $fieldAlias, trim($param))
                        ->setParameter('percentParam' . $fieldAlias, '%' . trim($param) . '%');
                }
            } else {
                $qb->setParameter('param' . $fieldAlias, $cleaned)
                    ->setParameter('percentParam' . $fieldAlias, '%' . $cleaned . '%');
            }
        }
        $qb->$whereLexem(new Orx($searcher));
    }

}