<?php

namespace YG\Phalcon\Utils;

class QueryParams
{
    static public function get(?array $addParams, ?string $removeParams = null): array
    {
        $params = $_GET;
        unset($params['_url']);

        if ($removeParams != null)
        {
            $arr = explode(',', $removeParams);
            foreach ($arr as $param)
                unset($params[$param]);
        }

        if ($addParams != null)
        {
            foreach ($addParams as $name => $value)
                $params[$name] = $value;
        }

        return $params;
    }

    static public function getCurrentUrl(): string
    {
        return $_REQUEST['_url'];
    }
}