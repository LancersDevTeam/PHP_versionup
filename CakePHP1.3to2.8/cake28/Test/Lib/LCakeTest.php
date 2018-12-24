<?php
namespace Test\App\Lib;

\App::uses('AppModel', 'Model');
\App::uses('Controller', 'Controller');
\App::uses('View', 'View');
\App::uses('CakeRequest', 'Network');
\App::uses('CakeResponse', 'Network');
\App::uses('ComponentCollection', 'Controller');
class LCakeTest extends \ControllerTestCase
{
    public $fixtures            = array();
    protected $components       = array();
    protected $models           = array();
    protected $helpers          = array();
    protected $exceptions       = array();
    protected $useAllFixtures   = false;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        // 全fixturesの設定。
        // setUp()が呼ばれる時点ではfixtureの処理が完了しているのでコンストラクタで設定する
        if ($this->useAllFixtures) {
            $this->useAllFixtures();
        }
    }

    public function setUp()
    {
        parent::setUp();

        // テスト間で影響が出ないようにテスト時はキャッシュを無効にしておく
        // 特定のテストで有効にしたい場合はテストメソッド内で設定を上書きする
        \Configure::write('Cache.disable', true);
        $this->loadComponents();
        $this->loadModels();
        $this->loadHelpers();
        $this->loadExceptions();
    }

    public function tearDown()
    {
        $this->unloadModels();
        $this->unloadComponents();
        parent::tearDown();
    }

    public function useAllFixtures()
    {
        $fixturesInstance = new \Fixtures;
        $this->fixtures = $fixturesInstance->fixtures;
    }

    public function loadModels()
    {
        foreach ($this->models as $model) {
            \App::uses($model, 'Model');
            $this->$model = new $model();
            $this->$model->useDbConfig = 'test';
        }
    }

    public function loadComponents()
    {
        foreach ($this->components as $component) {
            $className = "{$component}Component";
            \App::uses($className, 'Controller/Component');

            // namespaceに置き換え
            $className = "\\{$className}";
            $this->$component = new $className(new \ComponentCollection());
            $this->$component->startup(new \Controller(
                new \CakeRequest(),
                new \CakeResponse()
            ));
        }
    }

    /**
     * 独自処理
     * テスト側で定義されたHelperを読み込む
     */
    public function loadHelpers()
    {
        foreach ($this->helpers as $helper) {
            $className = "{$helper}Helper";
            \App::uses($className, 'View/Helper');

            // namespaceに置き換え
            $className = "\\{$className}";
            $this->$helper = new $className(new \View());
        }
    }

    /**
     * 独自処理
     * テスト側で定義されたExceptionを読み込む
     */
    public function loadExceptions()
    {
        foreach ($this->exceptions as $exception) {
            $className = "{$exception}Exception";
            \App::uses($className, 'Exceptions');
        }
    }

    /**
     * LCake13Testと互換性を持たせるためにメソッドに切り出している
     */
    public function bindModel($model, $binds)
    {
        $this->$model->bindModel($binds);
    }

    private function unloadModels()
    {
        $this->unload($this->models);
    }

    public function unloadComponents()
    {
        $this->unload($this->components);
    }

    private function unload($list)
    {
        foreach ($list as $class) {
            unset($this->$class);
        }
    }
}
