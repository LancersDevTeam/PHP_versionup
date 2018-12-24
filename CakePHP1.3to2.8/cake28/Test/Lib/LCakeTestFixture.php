<?php
namespace Test\App\Lib;

class LCakeTestFixture extends \CakeTestFixture
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

        /*
          $db->describe()の戻り値は元々$this->fieldsに定義していたのと同じ構造だった

          array(9) {
              ["id"]=> array(6) {
                  ["type"]     => string(7) "integer"
                  ["null"]     => bool(false)
                  ["default"]  => NULL
                  ["length"]   => int(11)
                  ["unsigned"] => bool(false)
                  ["key"]      => string(7) "primary"
              }
              ...
          }
         */
        return $db->describe($this->table);
    }
}
