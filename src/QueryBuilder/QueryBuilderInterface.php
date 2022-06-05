<?php

namespace YG\Phalcon\QueryBuilder;

use Phalcon\Mvc\Model\Query\BuilderInterface;
use Phalcon\Mvc\Model\QueryInterface;
use Phalcon\Paginator\RepositoryInterface;

/**
 * @method self addFrom(string $model, string $alias = null)
 * @method self andWhere(string $conditions, array $bindParams = [], array $bindTypes = [])
 * @method self betweenWhere(string $expr, mixed $minimum, mixed $maximum, string $operator = BuilderInterface::OPERATOR_AND)
 * @method self columns(string|array $columns)
 * @method self distinct(mixed $distinct)
 * @method self forUpdate(bool $forUpdate)
 * @method self from(string|array $models)
 * @method array getBindParams()
 * @method array getBindTypes()
 * @method string|array getColumns()
 * @method bool getDistinct()
 * @method string|array getFrom()
 * @method array getGroupBy()
 * @method string etHaving()
 * @method array getJoins()
 * @method string|array getLimit()
 * @method int getOffset()
 * @method string|array getOrderBy()
 * @method string getPhql()
 * @method QueryInterface getQuery()
 * @method string|array getWhere()
 * @method self groupBy(string|array $group)
 * @method self having(string $having)
 * @method self innerJoin(string $model, string $conditions = null, string $alias = null)
 * @method self inWhere(string $expr, array $values, string $operator = BuilderInterface::OPERATOR_AND)
 * @method self join(string $model, string $conditions = null, string $alias = null)
 * @method self leftJoin(string $model, string $conditions = null, string $alias = null)
 * @method self limit(int $limit, $offset = null)
 * @method string|array|null getModels()
 * @method self notBetweenWhere(string $expr, mixed $minimum, mixed $maximum, string $operator = BuilderInterface::OPERATOR_AND)
 * @method self offset(int $offset)
 * @method self orderBy(string $orderBy)
 * @method self orWhere(string $conditions, array $bindParams = [], array $bindTypes = [])
 * @method self rightJoin(string $model, string $conditions = null, string $alias = null)
 * @method self setBindParams(array $bindParams, bool $merge = false)
 * @method self setBindTypes(array $bindTypes, bool $merge = false)
 * @method self where(string $conditions, array $bindParams = [], array $bindTypes = [])
 */
interface QueryBuilderInterface
{
    /**
     * @param int $page
     * @param int $limit
     *
     * @return RepositoryInterface|PaginationRepository
     */
    public function executeToPagination(int $page, int $limit): RepositoryInterface;

    public function whereByArray(array $filters, $conditionType = 'and'): QueryBuilderInterface;
}