<?php
namespace App\Dao\Mongo;

use App\Dao\Mongo;
use Psr\Container\ContainerInterface;

class BookDao extends Mongo
{
    // constructor receives container instance
    function __construct(ContainerInterface $c){
        parent::__construct($c, 'book');
    }

    public function getAll()
    {
        $data = $this->findAll();

        return $data;
    }

    public function getById($id)
    {
        $data = $this->findOne(['_id' => intval($id)]);

        return $data;
    }

    public function getByTitle($title)
    {
        $regex = new \MongoDB\BSON\Regex(sprintf(".*%s.*", $title));
        $data = $this->find(['title' => $regex]);

        return $data;
    }

    public function addNew($data)
    {
        $result = $this->insert($data);

        return $result;
    }

    public function updateById($id, $data)
    {
        $result = $this->update(['_id' => intval($id)], $data);

        return $result;
    }

    public function deleteById($id)
    {
        $result = $this->delete(['_id' => intval($id)]);

        return $result;
    }
}
?>