<?php

namespace YG\Phalcon\Query;

/**
 * @property array        $filter
 * @property null|string  $sort
 * @property null|integer $page
 * @property null|integer $limit
 */
class AbstractPaginationQuery extends AbstractQuery
{
    /**
     * @var array
     */
    protected $filter;

    /**
     * @var null|string
     */
    protected $sort;

    /**
     * @var null|integer
     */
    protected $page;

    /**
     * @var null|integer
     */
    protected $limit;

    /**
     * @param array       $filter
     * @param string|null $sort
     * @param int|null    $page
     * @param int|null    $limit
     */
    public function __construct(array $filter, ?string $sort, ?int $page, ?int $limit)
    {
        $this->filter = $filter;
        $this->sort = ($sort != '' and $sort[0] == '-') ? substr($sort, 1, strlen($sort) - 1) . ' desc' : $sort;
        $this->page = $page ?? 1;
        $this->limit = $limit ?? 10;
    }
}