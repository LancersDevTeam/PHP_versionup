<?php

// ※このModelはCakePHP3に移行しました。関数追加はCakePHP3側で行ってください。

class City extends AppModel
{
    public $name = 'City';
    public $validate = [
        'id' => ['numeric'],
    ];

    public $cake3Table = null;

    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->cake3Table = Cake\ORM\TableRegistry::getTableLocator()->get('Cities');
    }

    public function getName($cityId)
    {
        return $this->cake3Table->getName($cityId);
    }

    public function checkIntegrity($prefecturalId, $cityId)
    {
        return $this->cake3Table->checkIntegrity($prefecturalId, $cityId);
    }
}
