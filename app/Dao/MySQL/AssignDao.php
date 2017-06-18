<?php
namespace App\Dao\MySQL;

use App\Dao\MySQL;
use Psr\Container\ContainerInterface;

class AssignDao extends MySQL
{
    // constructor receives container instance
    function __construct(ContainerInterface $c)
    {
        parent::__construct($c, 'assign');
    }

    public function getByRoleId($roleId)
    {
        $data = $this->find([
                'select' => 'route',
                'where'  => 'role_id = ?',
                'binds'  => [$roleId],
            ]);

        return $data;
    }

    public function batchAddWithDelete($roleId, $data)
    {
        $operations = [
            [
                'type' => 'delete',
                'query' => [
                    'where' => 'role_id = ?',
                    'binds' => [$roleId],
                ],
            ],
        ];

        if (!empty($data)) {
            $operations[] = [
                'type' => 'insert',
                'data' => $data,
            ];
        }

        $result = $this->doTransaction($operations);

        return $result;
    }
}
?>