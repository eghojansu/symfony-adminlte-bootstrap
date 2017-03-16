<?php

namespace AppBundle\Utils;

use Doctrine\ORM\Tools\Pagination\Paginator;

class Tools
{
    public static function createDateTime($time = null, $timezone = null)
    {
        try {
            return new \DateTime($time, new \DateTimeZone($timezone?:Config::DEFAULT_TIMEZONE));
        } catch (\Exception $e) {
            return null;
        }
    }

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

    public static function serialNumber(Connection $conn, $table, $field, $format, array $filter = null)
    {
        $filter = (array) $filter;
        $whereCriteria = null;
        $params = [];
        if ($filter) {
            $whereCriteria = 'WHERE '.array_shift($filter);
            $params = $filter;
        }
        $statement = "SELECT $field FROM $table $whereCriteria ORDER BY $field DESC LIMIT 1";
        $latestSerial = $conn->fetchColumn($statement, $params);

        $last = 0;
        $boundPattern = '/\{([a-z0-9\- _\.]+)\}/i';
        if ($latestSerial) {
            $pattern = preg_replace_callback($boundPattern, function($match) {
                return is_numeric($match[1])?
                    '(?<serial>'.str_replace('9', '[0-9]', $match[1]).')':
                    '(?<date>.{'.strlen(date($match[1])).'})';
            }, $format);
            if (preg_match('/^'.$pattern.'$/i', $latestSerial, $match)) {
                $last = $match['serial']*1;
            }
        }

        $serialNumber = preg_replace_callback($boundPattern, function($match) use ($last) {
            return is_numeric($match[1])?
                str_pad($last+1, strlen($match[1]), '0', STR_PAD_LEFT):
                date($match[1]);
        }, $format);

        return $serialNumber;
    }

    public static function randomString($len)
    {
        $chars = array_merge(range('a', 'z'), range(0, 9));
        shuffle($chars);

        return implode('', array_slice($chars, 0, $len));
    }
}
