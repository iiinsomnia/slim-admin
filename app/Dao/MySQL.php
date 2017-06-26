<?php
namespace App\Dao;

use App\Helpers\MailerHelper;
use Illuminate\Database\QueryException;
use Psr\Container\ContainerInterface;

/**
 * MySQL操作基类
 * 如有需要，请自行扩展
 * 文档参考Laravel查询构造器
 */
class MySQL
{
    private $_db;
    private $_table;
    private $_prefix;

    protected $container;

    /**
     * constructor receives container instance
     * @param ContainerInterface $di container instance
     * @param string $table 表名称
     * @param string $db 数据库配置名称，默认：mysql
     */
    public function __construct(ContainerInterface $c, $table, $db = 'mysql')
    {
        $this->_db = $c->get($db);
        $this->_table = $table;

        $settings = $c->get('settings')[$db];
        $this->_prefix = $settings['prefix'];

        $this->container = $c;
    }

    /**
     * 插入一条记录
     * @param array $data 插入的数据
     * @return int/false 返回记录ID
     */
    protected function insert($data)
    {
        try {
            $id = $this->_db::table($this->_table)->insertGetId($data);

            return $id;
        } catch (QueryException $e) {
            $logger = $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        }
    }

    /**
     * 批量插入记录
     * @param array $data 插入的数据
     * @return bool 返回是否成功
     */
    protected function batchInsert($data)
    {
        try {
            $success = $this->_db::table($this->_table)->insert($data);

            return $success;
        } catch (QueryException $e) {
            $logger = $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        }
    }

    /**
     * 更新记录
     * @param array $query 查询条件，如：
     *        [
     *            'where' => 'id = ?',
     *            'binds' => [1],
     *        ]
     * @param array $data 更新的数据
     * @return int/false 更新影响的行数
     */
    protected function update($query, $data)
    {
        $build = $this->_buildUpdate($query, $data);

        try {
            $affectRows = $this->_db::update($build['sql'], $build['binds']);

            return $affectRows;
        } catch (QueryException $e) {
            $logger = $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        }
    }

    /**
     * 获取记录数
     * @param array $query 查询条件数组，如：
     *        [
     *            'where' => 'id = ?',
     *            'bind'  => [1],
     *        ]
     * @param string $column 聚合字段，默认：*
     * @return int 返回记录数
     */
    protected function count($query = [], $column = '*')
    {
        $query['select'] = sprintf("COUNT(%s) AS count", $column);

        $build = $this->_buildQuery($query);
        $data = $this->_db::selectOne($build['sql'], $build['binds']);

        return $data->count;
    }

    /**
     * 查询单条记录
     * @param array $query 查询条件数组，如：
     *        [
     *            'select' => 'id, name',
     *            'where'  => 'id = ?',
     *            'binds'  => [1],
     *        ]
     * @return array 返回查询结果
     */
    protected function findOne($query)
    {
        $query['limit'] = 1;

        $build = $this->_buildQuery($query);
        $data = $this->_db::selectOne($build['sql'], $build['binds']);

        $result = $this->_toArray($data);

        return $result;
    }

    /**
     * 查询多条记录
     * @param array $query 查询条件数组，如：
     *        [
     *            'select' => 'a.id, a.name, b.name AS username',
     *            'join'   => ['LEFT JOIN slim_user AS b ON a.uid = b.id'],
     *            'where'  => 'a.id IN [?] AND a.status = ?,
     *            'order'  => 'a.id DESC',
     *            'offset' => 0,
     *            'limit'  => 10,
     *            'binds'  => [[1, 2], 1],
     *        ]
     * 注：join条件是一个数组，可以进行多个JOIN操作
     * @return array 返回查询结果
     */
    protected function find($query)
    {
        $build = $this->_buildQuery($query);
        $data = $this->_db::select($build['sql'], $build['binds']);

        $result = $this->_toArray($data, true);

        return $result;
    }

    /**
     * 查询所有记录
     * @param array $columns 查询的字段
     * @return array 返回查询结果
     */
    protected function findAll($columns = ['*'])
    {
        $select = implode(',', $columns);

        $sql = sprintf("SELECT %s FROM %s%s", $select, $this->_prefix, $this->_table);
        $data = $this->_db::select($sql);

        $result = $this->_toArray($data, true);

        return $result;
    }

    /**
     * 删除记录
     * @param array $query 查询条件，如：
     *        [
     *            'where' => 'id = ?',
     *            'binds' => [1],
     *        ]
     * @return int/false 更新影响的行数
     */
    protected function delete($query)
    {
        try {
            $build = $this->_buildDelete($query);
            $affectRows = $this->_db::delete($build['sql'], $build['binds']);

            return $affectRows;
        } catch (QueryException $e) {
            $logger = $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        }
    }

    /**
     * 事务操作
     * @param array $operations 操作集合 (插入、更新和删除)，如：
     *        [
     *            [
     *                'type'  => 'insert',
     *                'table' => 'article',
     *                'data'  => [插入的数据，若是批量插入，则是二维数组],
     *            ],
     *            [
     *                'type'  => 'update',
     *                'query' => [
     *                    'table' => 'user',
     *                    'where' => 'status = ?',
     *                    'binds' => [1],
     *                ]
     *                'data'  => ['status' => 0]',
     *            ],
     *            [
     *                'type'  => 'delete',
     *                'query' => [
     *                    'table' => 'user',
     *                    'where' => 'status = ?',
     *                    'binds' => [1],
     *                ],
     *            ],
     *        ]
     *        注：不传table字段，则操作当前表
     * @return bool 是否成功
     */
    protected function doTransaction($operations)
    {
        $this->_db::beginTransaction();

        try {
            foreach ($operations as $v) {
                switch ($v['type']) {
                    case 'insert':
                        $table = !empty($v['table']) ? $v['table'] : $this->_table;
                        $this->_db::table($table)->insert($v['data']);
                        break;
                    case 'update':
                        $build = $this->_buildUpdate($v['query'], $v['data']);
                        $this->_db::update($build['sql'], $build['binds']);
                        break;
                    case 'delete':
                        $build = $this->_buildDelete($v['query']);
                        $this->_db::delete($build['sql'], $build['binds']);
                        break;
                }
            }

            $this->_db::commit();

            return true;
        } catch (QueryException $e) {
            $this->_db::rollback();

            $logger = $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        }
    }

    // build update
    private function _buildUpdate($query, $data)
    {
        $table = $this->_table;
        $sets = [];
        $clauses = [];
        $binds = [];

        if (!empty($query['table'])) {
            $table = $query['table'];
        }

        $clauses[] = sprintf("UPDATE %s%s", $this->_prefix, $table);

        foreach ($data as $k => $v) {
            $sets[] = sprintf("%s = ?", $k);
            $binds[] = $v;
        }

        $clauses[] = sprintf("SET %s", implode(', ', $sets));

        if (!empty($query['where'])) {
            $clauses[] = sprintf("WHERE %s", $query['where']);
        }

        if (!empty($query['binds'])) {
            $binds = array_merge_recursive($binds, $query['binds']);;
        }

        $separator = ' ';
        $sql = implode($separator, $clauses);

        $this->_buildIn($sql, $binds);

        return [
            'sql'   => $sql,
            'binds' => $binds,
        ];
    }

    // bulid query
    private function _buildQuery($query)
    {
        $table = $this->_table;
        $clauses = [];
        $binds = [];

        if (!empty($query['select'])) {
            $clauses[] = sprintf("SELECT %s", $query['select']);
        } else {
            $clauses[] = "SELECT *";
        }

        if (!empty($query['table'])) {
            $table = $query['table'];
        }

        if (!empty($query['join'])) {
            $clauses[] = sprintf("FROM %s%s AS a", $this->_prefix, $table);

            foreach ($query['join'] as $v) {
                $clauses[] = $v;
            }
        } else {
            $clauses[] = sprintf("FROM %s%s", $this->_prefix, $table);
        }

        if (!empty($query['where'])) {
            $clauses[] = sprintf("WHERE %s", $query['where']);
        }

        if (!empty($query['group'])) {
            $clauses[] = sprintf("GROUP BY %s", $query['group']);
        }

        if (!empty($query['order'])) {
            $clauses[] = sprintf("ORDER BY %s", $query['order']);
        }

        if (!empty($query['limit'])) {
            $clauses[] = sprintf("LIMIT %s", $query['limit']);
        }

        if (!empty($query['offset'])) {
            $clauses[] = sprintf("OFFSET %s", $query['offset']);
        }

        if (!empty($query['binds'])) {
            $binds = $query['binds'];
        }

        $separator = ' ';
        $sql = implode($separator, $clauses);

        $this->_buildIn($sql, $binds);

        return [
            'sql'   => $sql,
            'binds' => $binds,
        ];
    }

    // build delete
    private function _buildDelete($query)
    {
        $table = $this->_table;
        $clauses = [];
        $binds = [];

        if (!empty($query['table'])) {
            $table = $query['table'];
        }

        $clauses[] = sprintf("DELETE FROM %s%s", $this->_prefix, $table);

        if (!empty($query['where'])) {
            $clauses[] = sprintf("WHERE %s", $query['where']);
        }

        if (!empty($query['binds'])) {
            $binds = $query['binds'];
        }

        $separator = ' ';
        $sql = implode($separator, $clauses);

        $this->_buildIn($sql, $binds);

        return [
            'sql'   => $sql,
            'binds' => $binds,
        ];
    }

    // build in
    private function _buildIn(&$sql, &$binds)
    {
        if (empty($binds)) {
            return;
        }

        foreach ($binds as $k => $v) {
            if (is_array($v)) {
                $placeholders = [];

                for ($i = 0; $i < count($v); $i++) {
                    $placeholders[] = '?';
                }

                $bindvar = sprintf("(%s)", implode(', ', $placeholders));
                $sql = preg_replace('/\[\?\]/', $bindvar, $sql, 1);

                array_splice($binds, $k, 1, $v);

                $this->_buildIn($sql, $binds);

                return;
            }
        }

        return;
    }

    // convert stdClass to array
    private function _toArray($data, $multiple = false)
    {
        if (empty($data)) {
            return [];
        }

        $result = [];

        if ($multiple) {
            foreach ($data as $obj) {
                $result[] = get_object_vars($obj);
            }
        } else {
            $result = get_object_vars($data);
        }

        return $result;
    }
}
?>