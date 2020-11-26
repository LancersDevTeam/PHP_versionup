<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * @SuppressWarnings(PHPMD)
 */
abstract class BaseTable extends Table
{
    private function _switchMaster(): void
    {
        $conn = $this->getConnection();
        if ($conn->inTransaction()) {
            return;
        }
        $conn->switchRole('master');
    }

    private function _switchReplica(): void
    {
        $conn = $this->getConnection();
        if ($conn->inTransaction()) {
            return;
        }
        $conn->switchRole('replica');
    }

    public function __call($method, $args)
    {
        if (preg_match('/^find(?:\w+)?By/', $method) > 0) {
            $this->_switchReplica();
            return parent::__call($method, $args);
        }
        $this->_switchMaster();
        return parent::__call($method, $args);
    }

    public function find(string $type = 'all', array $options = []): Query
    {
        $this->_switchReplica();
        return parent::find($type, $options);
    }

    public function findAll(Query $query, array $options): Query
    {
        $this->_switchReplica();
        return parent::findAll($query, $options);
    }

    public function findList(Query $query, array $options): Query
    {
        $this->_switchReplica();
        return parent::findList($query, $options);
    }

    public function findThreaded(Query $query, array $options): Query
    {
        $this->_switchReplica();
        return parent::findThreaded($query, $options);
    }

    public function get($primaryKey, $options = []): EntityInterface
    {
        $this->_switchReplica();
        return parent::get($primaryKey, $options);
    }

    public function findOrCreate($search, ?callable $callback = null, $options = []): EntityInterface
    {
        $this->_switchMaster();
        return parent::findOrCreate($search, $callback, $options);
    }

    public function query(): Query
    {
        $this->_switchMaster();
        return parent::query();
    }

    public function updateAll($fields, $conditions): int
    {
        $this->_switchMaster();
        return parent::updateAll($fields, $conditions);
    }

    public function deleteAll($conditions): int
    {
        $this->_switchMaster();
        return parent::deleteAll($conditions);
    }

    public function exists($conditions): bool
    {
        $this->_switchReplica();
        return parent::exists($conditions);
    }

    public function save(EntityInterface $entity, $options = [])
    {
        $this->_switchMaster();
        return parent::save($entity, $options);
    }

    public function saveOrFail(EntityInterface $entity, $options = []): EntityInterface
    {
        $this->_switchMaster();
        return parent::saveOrFail($entity, $options);
    }

    public function saveMany(iterable $entities, $options = [])
    {
        $this->_switchMaster();
        return parent::saveMany($entities, $options);
    }

    public function saveManyOrFail(iterable $entities, $options = []): iterable
    {
        $this->_switchMaster();
        return parent::saveManyOrFail($entities, $options);
    }

    public function delete(EntityInterface $entity, $options = []): bool
    {
        $this->_switchMaster();
        return parent::delete($entity, $options);
    }

    public function deleteMany(iterable $entities, $options = [])
    {
        $this->_switchMaster();
        return parent::deleteMany($entities, $options);
    }

    public function deleteManyOrFail(iterable $entities, $options = []): iterable
    {
        $this->_switchMaster();
        return parent::deleteManyOrFail($entities, $options);
    }

    public function deleteOrFail(EntityInterface $entity, $options = []): bool
    {
        $this->_switchMaster();
        return parent::deleteOrFail($entity, $options);
    }
}
