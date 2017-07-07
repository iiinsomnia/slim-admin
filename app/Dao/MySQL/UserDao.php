<?php
namespace App\Dao\MySQL;

use App\Dao\MySQL;
use Psr\Container\ContainerInterface;

class UserDao extends MySQL
{
    // constructor receives container instance
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c, 'user');
    }

    public function getByPagination($where, $binds, $offset, $limit)
    {
        $count = $this->count([
            'join'  => ['LEFT JOIN slim_role AS b ON a.role = b.id'],
            'where' => $where,
            'binds' => $binds,
        ]);

        $data = $this->find([
            'select' => 'a.id, a.username, a.phone, a.email, a.role, a.last_login_time, b.name AS rolename',
            'join'   => ['LEFT JOIN slim_role AS b ON a.role = b.id'],
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
            'select' => 'id, username, phone, email, role',
            'where'  => 'id = ?',
            'binds'  => [$id],
        ]);

        return $data;
    }

    public function getByAccount($account)
    {
        $data = $this->findOne([
            'select' => 'id, username, phone, email, password, salt, role',
            'where'  => 'username = ? OR phone = ? OR email = ?',
            'binds'  => [$account, $account, $account],
        ]);

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