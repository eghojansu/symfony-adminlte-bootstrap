<?php

namespace AppBundle\Utils;

use Doctrine\ORM\Tools\Pagination\Paginator;

class Tools
{
    public static function pagination(Paginator $paginator)
    {
        $maxRecord    = $paginator->count();
        $query        = $paginator->getQuery();
        $limit        = $query->getMaxResults();
        $offset       = $query->getFirstResult();
        $inPageRecord = $paginator->getIterator()->count();
        $currentPage  = ($offset ? $limit / $offset : 0) + 1;
        $maxPages     = ceil($maxRecord / $limit);
        $firstNumber  = $offset;

        $data = [
            'totalRecord'=>$maxRecord,
            'inPageRecord'=>$inPageRecord,
            'currentPage'=>$currentPage,
            'maxPages'=>$maxPages,
            'firstNumber'=>$firstNumber,
        ];

        return $data;
    }
}
