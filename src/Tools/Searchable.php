<?php

namespace Keros\Tools;


interface Searchable
{
    /**
     * @return array Specify the list of fields in an object that a search should be applied to
     */
    public static function getSearchFields(): array;
}