<?php
namespace App\Dao;

use App\Helpers\MailerHelper;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Exception\InvalidArgumentException;
use MongoDB\Exception\RuntimeException;
use MongoDB\Exception\UnsupportedException;
use Psr\Container\ContainerInterface;

/**
 * mongo操作基类
 * 如有需要，请自行扩展
 * 文档地址：https://docs.mongodb.com/php-library/master/tutorial/install-php-library/
 */
class Mongo
{
    private $_collection;
    private $_sequence;
    private $_seqId;

    protected $container;

    /**
     * constructor receives container instance
     * @param ContainerInterface $di container instance
     * @param string $db 数据库名称
     * @param string $collection 集合名称
     */
    public function __construct(ContainerInterface $c, $db, $collection){
        $this->_collection = $c->mongo->$db->$collection;
        $this->_sequence = $c->mongo->$db->sequence;
        $this->_seqId = $collection;

        $this->container = $c;
    }

    /**
     * 插入单条记录
     * @param array $query 查询条件
     * @param array $data 插入数据
     * @return int/bool 影响的行数
     */
    protected function insert($data)
    {
        try {
            $id = $this->_refreshSequence();
            $data['_id'] = $id;
            $result = $this->_collection->insertOne($data);

            return $result->getInsertedId();
        } catch (InvalidArgumentException $e) {
            $this->_refreshSequence(-1);

            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (BulkWriteException $e) {
            $this->_refreshSequence(-1);

            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (RuntimeException $e) {
            $this->_refreshSequence(-1);

            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        }
    }

    /**
     * 批量插入（失败数据会回滚）
     * @param array $query 查询条件
     * @param array $data 插入数据
     * @return int/bool 影响的行数
     */
    protected function batchInsert($data)
    {
        $count = count($data);

        try {
            foreach ($data as &$value) {
                $id = $this->_refreshSequence();
                $value['_id'] = $id;
            }

            $result = $this->_collection->insertMany($data);

            return $result->getInsertedCount();
        } catch (InvalidArgumentException $e) {
            $this->_refreshSequence(~$count + 1);

            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (BulkWriteException $e) {
            $this->_refreshSequence(~$count + 1);

            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (RuntimeException $e) {
            $this->_refreshSequence(~$count + 1);

            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        }
    }

    /**
     * 更新单条记录
     * @param array $query 查询条件
     * @param array $data 更新数据
     * @return int/bool 影响的行数
     */
    protected function update($query, $data)
    {
        try {
            $result = $this->_collection->updateMany($query, ['$set' => $data]);

            return $result->getModifiedCount();
        } catch (UnsupportedException $e) {
            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (InvalidArgumentException $e) {
            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (BulkWriteException $e) {
            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (RuntimeException $e) {
            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        }
    }

    /**
     * 查询单条记录
     * @param array $query 查询条件
     * @param array $options 查询可选项
     * @return MongoDB\Model\BSONDocument
     */
    protected function findOne($query, $options = [])
    {
        $data = $this->_collection->findOne($query, $options = []);

        return $data;
    }

    /**
     * 查询多条记录
     * @param array $query 查询条件
     * @param array $options 查询可选项
     * @return array
     */
    protected function find($query, $options = [])
    {
        $cursor = $this->_collection->find($query, $options = []);

        $data = [];

        foreach ($cursor as $doc) {
           $data[] = $doc;
        }

        return $data;
    }

    /**
     * 查询所有记录
     * @return array
     */
    protected function findAll()
    {
        $cursor = $this->_collection->find();

        $data = [];

        foreach ($cursor as $doc) {
           $data[] = $doc;
        }

        return $data;
    }

    /**
     * 删除单条记录
     * @param array $query 查询条件
     * @return int/bool 影响的行数
     */
    protected function delete($query)
    {
        try {
            $result = $this->_collection->deleteMany($query);

            return $result->getDeletedCount();
        } catch (UnsupportedException $e) {
            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (InvalidArgumentException $e) {
            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (BulkWriteException $e) {
            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        } catch (RuntimeException $e) {
            $this->container->logger->error($e->getMessage());

            if (env('ERROR_MAIL', false)) {
                MailerHelper::sendErrorMail($e);
            }

            return false;
        }
    }

    /**
     * 生成 Mongo 文档当前自增的_id值
     * @param int $inc 增量，默认：1
     * @return int 当前自增的_id值
     */
    private function _refreshSequence($inc = 1)
    {
        $this->_sequence->updateOne(
            ['_id' => $this->_seqId],
            ['$inc' => ['seq' => $inc]],
            ['upsert' => true]
        );

        $upsertedDocument = $this->_sequence->findOne([
            '_id' => $this->_seqId,
        ]);

        return $upsertedDocument->seq;
    }
}
?>