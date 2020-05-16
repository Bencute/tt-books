<?php


namespace frontend\helper;


use system\helper\FormHtml;

/**
 * Class FormHtmlPagination
 * @package frontend\helper
 */
class FormHtmlPagination extends FormHtml
{
    public static function getLink(int $page, string $nameUrlParam = 'page'): string
    {
        $request = $_SERVER['REQUEST_URI'];
        $route = explode('?', $request)[0];

        $params = $_GET;
        unset($params[$nameUrlParam]);

        $linkParams = implode(
            '&',
            array_merge(
            /* array [name=>value] -> string "name=value" */
                array_map(
                    fn($name, $value) => $name . '=' . $value,
                    array_keys($params),
                    $params
                ),
                ($page === 1 ? [] : [$nameUrlParam . '=' . $page])
            )
        );

        if (empty($linkParams))
            return $route;
        else
            return $route . '?' . $linkParams;
    }
}