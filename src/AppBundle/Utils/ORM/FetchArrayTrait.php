<?php

namespace AppBundle\Utils\ORM;

trait FetchArrayTrait
{
    public function fetchArray(array $fields)
    {
        $data = [];
        foreach ($this->findAll() as $item) {
            $array = [];
            foreach ($fields as $a => $b) {
                $field = is_callable($b) ? $a : $b;
                $get = 'get'.ucfirst($field);
                $datum = $item->$get();
                $array[] = is_callable($b) ? call_user_func_array($b, [$datum]) : $datum;
            }
            $data[] = $array;
        }

        return $data;
    }
}
