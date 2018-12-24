<?php
namespace Test\App\Lib;

class LCake13TestFixture extends \CakeTestFixture
{
    public function __construct()
    {
        if ($this->table === null || $this->table === '') {
            throw new \Exception('$this->tableを定義してください。');
        }

        // defaultのスキーマから動的に$fieldsを定義する
        // クラスプロパティ定義時に式を書けないのでコンストラクタで代入している
        // $this->filedsが空のままparent::__construct()が先に走ると落ちるので処理順序に注意
        $this->fields = $this->detectFields();
        parent::__construct();
    }

    private function detectFields()
    {
        $db = \ConnectionManager::getDataSource('default');
        $model = new LCake13TestFixtureModel(array('table' => $this->table, 'name' => 'describe'));
        // 戻り値はほとんど \Test\App\Lib\LCakeTestFixtureと同じ
        // こちらはunsignedの定義だけ取得できない see #12584
        return $db->describe($model);
    }
}

/**
 * defaultのDatasourceからテーブル定義を取得するためのモデル
 */
class LCake13TestFixtureModel extends \Model
{
    var $useDbConfig = 'default';
    var $cacheSources = false;
}
