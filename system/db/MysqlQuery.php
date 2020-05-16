<?php


namespace system\db;


use Exception;
use PDOStatement;
use Sys;
use system\exception\DbException;

/**
 * Class MysqlQuery
 * @package system\db
 */
class MysqlQuery
{
    /**
     * @var array
     */
    public static array $defaultLimit = ['count' => 20];

    /**
     * $condition format: [
     *      'columns' => [
     *          nameColumn1 => value1,
     *          nameColumn2 => value2,
     *          ...
     *      ],
     *      'expressions' => [
     *          'native RLIKE exptression1',
     *          'native RLIKE exptression2',
     *          ...
     *      ]
     *      'limit' => [
     *          'offset' => integer,
     *          'count' => integer,
     *      ],
     *      'order' => [
     *          'nameColumn' => 'DESC' | 'ASK',
     *          'nameColumn2' => 'DESC' | 'ASK',
     *          ...
     *      ],
     * ]
     *
     * @param string $tableName
     * @param array $condition
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function select(string $tableName, array $condition)
    {
        $sql = 'SELECT * FROM ' . $tableName;

        $sqlParams = [];

        $expressions = $condition['expressions'] ?? [];
        $columns = $condition['columns'] ?? null;
        if (!is_null($columns) && !empty($columns) || !empty($expressions)) {
            $sqlWhereImplode = [];

            if (!empty($expressions)){
                $sqlWhereImplode = $expressions;
            }

            if (!is_null($columns) && !empty($columns)) {
                $params = self::generateParams($columns);
                $sqlParams = $params['paramsSql'];
                $sqlWhereColumns = implode(' AND ', $params['strsSql']);
                $sqlWhereImplode[] = $sqlWhereColumns;
            }

            $sqlWhere = implode(' AND ', $sqlWhereImplode);

            $sql .=  ' WHERE ' . $sqlWhere ;
        }

        if (isset($condition['order'])) {
            $orderParams = [];
            foreach ($condition['order'] as $column => $order) {
                $orderParams[] = $column . ' ' . $order;
            }
            if (!empty($orderParams)) {
                $strOrderParams = implode(',', $orderParams);
                $sql .= ' ORDER BY ' . $strOrderParams;
            }
        }

        $sqlLimitOffsetImplode = [];

        if (isset($limit['offset']) && !is_null($limit['offset'])) {
//            $sqlLimit = (int) $limit['offset'] . ', ' . $sqlLimit;
            $sqlLimitOffsetImplode[] = (int) $limit['offset'];
        }

        if (isset($condition['limit']['count'])) {
//        $limit = $condition['limit'] ?? self::$defaultLimit;
            $sqlLimitOffsetImplode[] = (int) $condition['limit']['count'];
        }

//        $sql .= ' LIMIT ' . $sqlLimit;
        $sql .= ' LIMIT ' . implode(', ', $sqlLimitOffsetImplode);

        $sql .= ';';

        return self::execute($sql, $sqlParams);
    }

    /**
     * $values format: [
     *      nameColumn1 => value1,
     *      nameColumn2 => value2,
     *      ....
     * ]
     *
     * @param string $tableName
     * @param array $attributeValues
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function insert(string $tableName, array $attributeValues)
    {
        $params = self::generateParams($attributeValues);

        $sqlColumns = implode(',', array_keys($attributeValues));

        $sqlParamValues = implode(',', array_keys($params['paramsSql']));

        $sql = 'INSERT INTO ' . $tableName . ' (' . $sqlColumns . ') VALUES (' . $sqlParamValues . ');';

        $sqlParams = $params['paramsSql'];

        return self::execute($sql, $sqlParams);
    }

    /**
     * $attributeValues format: [
     *      nameColumn1 => value1,
     *      nameColumn2 => value2,
     *      ...
     * ]
     *
     * @param string $tableName
     * @param string $namePrimaryKey
     * @param array $attributeValues
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function update(string $tableName, string $namePrimaryKey, array $attributeValues)
    {
        $params = self::generateParams($attributeValues);

        $strSqlParamsAttributes = implode(', ', $params['strsSql']);

        $sqlParams = $params['paramsSql'];

        $sqlParams[':id'] = $attributeValues[$namePrimaryKey];
        $sqlWhere = $namePrimaryKey . '=:id';

        $sql = 'UPDATE ' . $tableName . ' SET ' . $strSqlParamsAttributes . ' WHERE ' . $sqlWhere . ';';

        return self::execute($sql, $sqlParams);
    }

    /**
     * $values format: [
     *      nameParam1 => value1,
     *      nameParam2 => value2,
     *      ....
     * ]
     *
     * return format: [
    *       'values => [
     *          nameColumn1 => [
     *              'param' = nameParamValue1,
     *              'val' = value1,
     *              ...
     *          ],
     *          nameColumn2 => [
     *              'param' = nameParamValue2,
     *              'val' = value2,
     *              ...
     *          ],
     *          ......
     *      ],
     *      'strsSql' => [
     *          [0] => 'nameColumn1=nameParamValue1',
     *          [1] => 'nameColumn2=nameParamValue2',
     *          ...
     *      ]
     *      'paramsSql' => [
     *          nameParamValue1 => value1,
     *          nameParamValue2 => value2,
     *          ...
     *      ]
     * ]
     *
     * @param $values
     * @return array
     */
    private static function generateParams($values): array
    {
        $params = [];
        $i = 0;

        foreach ($values as $column => $value) {
            $nameParam = ':v' . $i;
            $params['values'][$column]['nameParam'] = $nameParam;
            $params['values'][$column]['value'] = $value;

            $params['strsSql'][] = $column . '=' . $nameParam;
            $params['paramsSql'][$nameParam] = $value;
            $i++;
        }

        return $params;
    }

    /**
     * $params format: [
     *      nameParam1 => value1,
     *      nameParam2 => value2,
     *      ....
     * ]
     *
     * @param PDOStatement $query
     * @param array $params
     * @return bool
     */
    public static function bindParam(PDOStatement $query, array $params): bool
    {
        foreach ($params as $nameParam => $value) {
            if (!$query->bindValue($nameParam, $value))
                return false;
        }
        return true;
    }

    /**
     * Return false or \PDOStatement
     *
     * @param string $sql
     * @param array $params
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function execute(string $sql, array $params)
    {
        $db = Sys::getApp()->getDB();

        $query = $db->prepare($sql);

        if ($query === false)
            return false;

        if (!self::bindParam($query, $params))
            return false;

//        $query->debugDumpParams();
        $result = $query->execute();

        if (!$result)
            throw new DbException($query->errorInfo()[2]);

//                $query->debugDumpParams();
//                echo "\nPDOStatement::errorInfo():\n";
//                $arr = $query->errorInfo();
//                print_r($arr);

        return $result ? $query : $result;
    }

    /**
     * @param string $tableName
     * @param string $namePrimaryKey
     * @param $valuePrimaryKey
     * @throws Exception
     * @return bool|PDOStatement
     */
    public static function delete(string $tableName, string $namePrimaryKey, $valuePrimaryKey)
    {
        $sqlParams[':id'] = $valuePrimaryKey;
        $sqlWhere = $namePrimaryKey . '=:id';

        $sql = 'DELETE FROM ' . $tableName . ' WHERE ' . $sqlWhere . ';';

        return self::execute($sql, $sqlParams);
    }

    /**
     * @param string $tableName
     * @param array $condition
     * @return bool|PDOStatement
     * @throws Exception
     */
    public static function count(string $tableName, array $condition = [])
    {
        $sql = 'SELECT COUNT(*) FROM ' . $tableName;

        $sqlParams = [];

        if (!empty($condition)) {
            $params = self::generateParams($condition);
            $sqlParams = $params['paramsSql'];
            $sqlWhere = implode(', ', $params['strsSql']);
            $sql .=  ' WHERE ' . $sqlWhere ;
        }

        return self::execute($sql, $sqlParams);
    }
}