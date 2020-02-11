<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Prefecturals Model
 *
 * @property \App\Model\Table\RegionsTable&\Cake\ORM\Association\BelongsTo $Regions
 * @property \App\Model\Table\CitiesTable&\Cake\ORM\Association\HasMany $Cities
 * @property \App\Model\Table\CorporationsTable&\Cake\ORM\Association\HasMany $Corporations
 * @property \App\Model\Table\FlbMycoClientsTable&\Cake\ORM\Association\HasMany $FlbMycoClients
 * @property \App\Model\Table\FlbMycoUsersTable&\Cake\ORM\Association\HasMany $FlbMycoUsers
 * @property \App\Model\Table\LabCampaignsTable&\Cake\ORM\Association\HasMany $LabCampaigns
 * @property \App\Model\Table\LabCampusesTable&\Cake\ORM\Association\HasMany $LabCampuses
 * @property \App\Model\Table\UserProfilesTable&\Cake\ORM\Association\HasMany $UserProfiles
 *
 * @method \App\Model\Entity\Prefectural get($primaryKey, $options = [])
 * @method \App\Model\Entity\Prefectural newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Prefectural[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Prefectural|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Prefectural saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Prefectural patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Prefectural[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Prefectural findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PrefecturalsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('prefecturals');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name', '入力してください');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['region_id'], 'Regions'));

        return $rules;
    }

    /**
     * 都道府県名を返す
     *
     * @param int|null $prefecturalId
     * @return string|null 都道府県名
     * @access public
     */
    // TODO: この関数は必要ないので削除（呼び出し側はワンライナーで書く）
    public function getName(?int $prefecturalId) : ?string
    {
        if (!isset($prefecturalId)) {
            return null;
        }
        $result = $this->find()->select(['name'])->where(['id' => $prefecturalId])->first();
        return isset($result) ? $result->name : null;
    }
}
