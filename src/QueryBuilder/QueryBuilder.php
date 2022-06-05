<?php

namespace YG\Phalcon\QueryBuilder;

use Phalcon\Di\Injectable;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Query\BuilderInterface;
use Phalcon\Paginator\PaginatorFactory;
use Phalcon\Paginator\RepositoryInterface;

class QueryBuilder extends Injectable implements QueryBuilderInterface
{
    protected Builder $builder;

    public function __construct()
    {
        $this->builder = new Builder();
    }

    public function executeToPagination(int $page, int $limit): RepositoryInterface
    {
        $paginator = (new PaginatorFactory())->newInstance('queryBuilder',
            [
                'builder' => $this->builder,
                'limit' => $limit,
                'page' => $page,
                'repository' => new PaginationRepository()
            ]
        );

        return $paginator->paginate();
    }

    protected function orderBy(?string $orderBy): self
    {
        if ($orderBy != '')
            $this->builder->orderBy($orderBy);

        return $this;
    }

    public function whereByArray(array $filters, $conditionType = 'and'): RepositoryInterface
    {
        $conditions = [];
        $bindParams = [];
        foreach ($filters as $key => $val)
        {
            if (is_array($val))
            {
                if (!isset($val[1]) or $val[1] == '')
                    continue;

                $name = $val[0];
                $value = $val[1];
                $compareType = $val[2];
                $value2 = array_key_exists(3, $val) ? $val[3] : null;
                $cast = array_key_exists(4, $val) ? $val[4] : null;

                if ($compareType == 'between' and $value2 == null)
                    $compareType = '=';


                $paramName = $name;
                $paramName = str_replace('.', '_', $paramName);

                if ($cast != null)
                {
                    switch ($cast)
                    {
                        case 'json':
                            $jsonName = array_key_exists(5, $val) ? $val[5] : null;
                            $paramName = $jsonName;
                            $name = "JSON_UNQUOTE(JSON_EXTRACT(".$name.", '$." . $jsonName . "'))";
                            break;
                        default:
                            $name = 'cast(' . $name . ' as ' . $cast . ')';
                    }
                }


                switch ($compareType)
                {
                    case 'like' :
                        $conditions[] = $name . ' like :' . $paramName . ":";
                        $bindParams[$paramName] = '%' . $value . '%';
                        break;
                    case 'between':
                        $conditions[] = $name . ' between :' . $paramName . ': and :' . $paramName . '_:';
                        $bindParams[$paramName] = $value;
                        $bindParams[$paramName . '_'] = $value2;
                        break;
                    default:
                        $conditions[] = $name . $compareType . ':' . $paramName . ":";
                        $bindParams[$paramName] = $value;
                }
            }
            else
            {
                if ($val != '' and $val != 'null')
                {
                    $conditions[] = $key . " like :" . $key . ":";
                    $bindParams[$key] = '%' . $val . '%';
                }
            }
        }

        if (count($conditions) > 0)
        {
            $this->builder->andWhere(join(' ' . $conditionType . ' ', $conditions), $bindParams);
        }

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name))
        {
            return call_user_func_array([$this, $name], $arguments);
        }
        elseif (method_exists($this->builder, $name))
        {
            $result = call_user_func_array([$this->builder, $name], $arguments);

            if ($result instanceof BuilderInterface)
                return $this;

            return $result;
        }
    }
}