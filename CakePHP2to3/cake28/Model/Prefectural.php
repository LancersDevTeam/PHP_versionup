<?php

// ※このModelはCakePHP3に移行しました。関数追加はCakePHP3側で行ってください。

/**
 * Prefectural model class
 *
 * @copyright Copyright 2013, Lancers Inc. (http://www.lancers.co.jp)
 * @link http://www.lancers.co.jp
 */
class Prefectural extends AppModel
{
    public $name = 'Prefectural';
    public $validate = [
        'id'        => ['numeric'],
        'region_id' => ['numeric'],
        'name'      => [
            [
                'rule' => ['notBlank'],
                'required' => true,
                'last' => true,
                'message' => '入力してください'
            ]
        ],
    ];

    public $cake3Table = null;

    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->cake3Table = Cake\ORM\TableRegistry::getTableLocator()->get('Prefecturals');
    }

    public function getName($prefecturalId)
    {
        return $this->cake3Table->getName($prefecturalId);
    }
}
