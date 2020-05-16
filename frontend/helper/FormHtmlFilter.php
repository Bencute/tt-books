<?php


namespace frontend\helper;


use Sys;
use system\helper\FormHtml;
use frontend\application\Application;
use frontend\model\Task;

/**
 * Class FormHtmlFilter
 * @package frontend\helper
 */
class FormHtmlFilter extends FormHtml
{
    public static function getLink(string $nameFilter, string $nameUrlParam = 'sort'): string
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
                [$nameUrlParam . '=' . $nameFilter]
            )
        );

        return $route . '?' . $linkParams;
    }

    public static function getTitleFilter(array $filter): string
    {
        return Sys::mId('app', $filter['title']);
    }

    public static function getListSupportFilters(): array
    {
        return Task::getFilters();
    }
}