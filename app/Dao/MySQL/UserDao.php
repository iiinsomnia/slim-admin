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

    public function getById($id)
    {
        $data = $this->findOne([
                'select' => 'id, username, email, phone, role, last_login_ip, last_login_time',
                'where'  => 'id = ?',
                'binds'  => [$id],
            ]);

        return $data;
    }

    public function getByName($username)
    {
        $data = $this->findOne([
                'select' => 'id, username, email, phone, password, salt, role, last_login_ip, last_login_time',
                'where'  => 'username = ?',
                'binds'  => [$username],
            ]);

        return $data;
    }

    public function getByPhone($phone)
    {
        $data = $this->findOne([
                'select' => 'id, username, email, phone, role, last_login_ip, last_login_time',
                'where'  => 'phone = ?',
                'binds'  => [$phone],
            ]);

        return $data;
    }

    public function getByEmail($email)
    {
        $data = $this->findOne([
                'select' => 'id, username, email, phone, role, last_login_ip, last_login_time',
                'where'  => 'email = ?',
                'binds'  => [$email],
            ]);

        return $data;
    }

    public function add($data)
    {
        $result = $this->insert($data);

        return $result;
    }

    public function updateById($id, $data)
    {
        $result = $this->update([
                'where' => 'id = ?',
                'binds' => [$id],
            ], $data);

        return $result;
    }

    public function deleteById($id)
    {
        $result = $this->delete([
                'where' => 'id = ?',
                'binds' => [$id],
            ]);

        return $result;
    }
}
?>