<?php

namespace Keros\Entities\Core;

use Doctrine\Common\Collections\Criteria;
use Keros\Tools\Searchable;

/**
 * Class RequestParameters
 * Extra request parameters including paging, search and ordering.
 * @package Keros\Entities\Core
 */
class RequestParameters
{
    /**
     * @var array - The array of strings to search for in the entity - the searched fields depend on the entity.
     * Default is empty
     */
    public $search;
    /**
     * @var string - The field by which to order by. Default depends on entity
     */
    public $orderBy;
    /**
     * @var string - asc or desc. Which way to order. Default asc.
     */
    public $order;
    /**
     * @var integer - the requested page number, starting at 0 (indexed). Default  0
     */
    public $pageNumber;
    /**
     * @var integer - number of entries per page. Minimum 10, maximum 100, default 25
     */
    public $pageSize;
    /**
     * @var array the fields of the entity to search - usually defined by getSearchFields in the entitie's class
     */
    private $searchFields;

    /**
     * RequestParameters constructor.
     * @param array $params the parameters from the Request
     * @param Searchable $class the class of the object searched
     */
    public function __construct(array $params, array $searchFields)
    {
        // Array of search values for member with the parameter search
        if (isset($params['search'])) {
            $this->search = explode(" ", $params['search']);
        }

        //Array of search values for member with the parameter position
        if (isset($params['positionId'])) {
            $this->search = explode(" ", $params['positionId']);
        }

        //Array of search values for member with the parameter year
        if (isset($params['year'])) {
            $this->search = explode(" ", $params['year']);
        }

        $this->searchFields = $searchFields;


        // Page number
        $this->pageNumber = 0; // Default value
        if (isset($params['pageNumber'])) {
            $pageNumber = (int)$params['pageNumber'];
            if ($pageNumber >= 0) {
                $this->pageNumber = $pageNumber;
            }
        }

        // Page Size
        $this->pageSize = 25; // Default value
        if (isset($params['pageSize'])) {
            $pageSize = (int)$params['pageSize'];
            if ($pageSize >= 10 && $pageSize <= 100) {
                $this->pageSize = $pageSize;
            }
        }

        // Order By
        if (isset($params['orderBy'])) {
            $this->orderBy = strtolower($params['orderBy']);
        }

        // Order
        if (isset($params['order']) && strtolower($params['order']) == "desc") {
            $this->order = Criteria::DESC;
        } else {
            $this->order = Criteria::ASC;
        }
    }

    public function getCriteria(): Criteria
    {
        $expr = Criteria::expr();
        $search = Criteria::create();
        if (!empty($this->search)) {
            foreach ($this->search as $s) {
                if (isset($s) && isset($this->searchFields)) {
                    foreach ($this->searchFields as $value) {
                        $search = $search->orWhere($expr->contains($value, $s));
                    }
                }
            }
        }
        if (isset($this->orderBy)) {
            $search = $search->orderBy(array($this->orderBy => $this->order));
        }
        $search = $search
            ->setFirstResult($this->pageNumber * $this->pageSize)
            ->setMaxResults(($this->pageNumber + 1) * $this->pageSize);
        return $search;
    }

    public function getCountCriteria(): Criteria
    {
        $expr = Criteria::expr();
        $search = Criteria::create();
        if (!empty($this->search)) {
            foreach ($this->search as $s) {
                if (isset($s) && isset($this->searchFields)) {
                    foreach ($this->searchFields as $value) {
                        $search = $search->orWhere($expr->contains($value, $s));
                    }
                }
            }
        }
        return $search;
    }
}