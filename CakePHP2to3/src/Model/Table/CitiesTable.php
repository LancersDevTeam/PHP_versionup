<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cities Model
 *
 * @property \App\Model\Table\PrefecturalsTable&\Cake\ORM\Association\BelongsTo $Prefecturals
 * @property \App\Model\Table\CorporationsTable&\Cake\ORM\Association\HasMany $Corporations
 * @property \App\Model\Table\UserProfilesTable&\Cake\ORM\Association\HasMany $UserProfiles
 *
 * @method \App\Model\Entity\City get($primaryKey, $options = [])
 * @method \App\Model\Entity\City newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\City[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\City|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\City saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\City patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\City[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\City findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CitiesTable extends AppTable
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

        $this->setTable('cities');
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
            ->notEmptyString('name');

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
        $rules->add($rules->existsIn(['prefectural_id'], 'Prefecturals'));

        return $rules;
    }

    /**
     * 市区町村名を返す
     *
     * @param int|null $cityId 市区町村ID
     * @return string|null 市区町村名
     * @access public
     */
    // TODO: この関数は必要ないので削除（呼び出し側はワンライナーで書く）
    public function getName(?int $cityId) : ?string
    {
        if (!isset($cityId)) {
            return null;
        }
        $result = $this->find()->select(['name'])->where(['id' => $cityId])->first();
        return isset($result) ? $result->name : null;
    }

    /**
     * 都道府県ID, 市区町村IDの整合性が正しいかチェックする
     *
     * 「東京都横浜市」のような不正データで無いことを検証する
     *
     * @param int $prefecturalId 都道府県id
     * @param int $cityId 市区町村id
     * @return bool
     * @access public
     */
    // TODO: この関数は必要ないので削除（呼び出し側ごと削除）
    public function checkIntegrity(int $prefecturalId, int $cityId) : bool
    {
        $result = $this->find()->where([
            'id' => $cityId,
            'prefectural_id' => $prefecturalId,
        ])->count();

        return $result > 0;
    }
}
