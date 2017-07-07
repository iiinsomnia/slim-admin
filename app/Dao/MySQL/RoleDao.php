<?php
namespace App\Dao\MySQL;

use App\Dao\MySQL;
use Psr\Container\ContainerInterface;

class RoleDao extends MySQL
{
    // constructor receives container instance
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c, 'role');
    }

    public function getByPagination($where, $binds, $offset, $limit)
    {
        $count = $this->count([
            'where' => $where,
            'binds' => $binds,
        ]);

        $data = $this->find([
            'select' => 'id, name',
            'where'  => $where,
            'limit'  => $limit,
            'offset' => $offset,
            'binds'  => $binds,
        ]);

        return [
            'count' => $count,
            'data'  => $data,
        ];
    }

    public function getById($id)
    {
        $data = $this->findOne([
            'select' => 'id, name',
            'where'  => 'id = ?',
            'binds'  => [$id],
        ]);

        return $data;
    }

    public function getAll()
    {
        $data = $this->findAll(['id', 'name']);

        return $data;
    }

    public function addNewRecord($data)
    {
        $id = $this->insert($data);

        return $id;
    }

    public function updateById($id, $data)
    {
        $rows = $this->update([
            'where' => 'id = ?',
            'binds' => [$id],
        ], $data);

        return $rows;
    }

    public function deleteById($id)
    {
        $rows = $this->delete([
            'where' => 'id = ?',
            'binds' => [$id],
        ]);

        return $rows;
    }
}
?>