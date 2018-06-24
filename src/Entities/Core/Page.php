<?php

namespace Keros\Entities\Core;
use JsonSerializable;


class Page implements JsonSerializable
{
    private $content;
    private $meta;

    public function __construct($content, $requestParameters, $totalCount){
        $this->content = $content;
        $this->meta = new Meta($requestParameters, $totalCount);
    }

    public function jsonSerialize()
    {
        return [
            'content' => $this->content,
            'meta' => $this->meta
        ];
    }
}

class Meta
{
    public $page;
    public $totalPages;
    public $totalItems;
    public $itemsPerPage;

    public function __construct($requestParameters, $totalCount)
    {
        $this->page = $requestParameters->pageNumber;
        $this->totalPages = ceil($totalCount / $requestParameters->pageSize);
        $this->totalItems = $totalCount;
        $this->itemsPerPage = $requestParameters->pageSize;
    }
}