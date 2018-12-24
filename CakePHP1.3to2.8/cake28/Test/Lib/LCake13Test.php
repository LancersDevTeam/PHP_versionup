<?php
namespace Test\App\Lib;

require_once CAKE_TESTS_LIB . 'cake_test_model.php';
require_once CAKE_TESTS_LIB . 'cake_test_fixture.php';

/**
 * Cake1.3でもPHPUnitを実行できるように拡張したクラス
 * ほとんどの処理は cake/tests/lib/cake_test_case.php から持ってきている
 */
abstract class LCake13Test extends \PHPUnit_Framework_TestCase
{
    public $fixtures      = array();
    protected $components = array();
    protected $models     = array();
    protected $behaviors  = array();
    protected $helpers    = array();
    protected $exceptions = array();
    protected $useAllFixtures = false;

    /**
     * cake/tests/lib/cake_test_case.php 参照
     */
    protected $autoFixtures = true;

    /**
     * cake/tests/lib/cake_test_case.php 参照
     */
    protected $dropTables = true;

    /**
     * cake/tests/lib/cake_test_case.php 参照
     */
    private $_fixtureClassMap = array();

    /**
     * cake/tests/lib/cake_test_case.php 参照
     */
    private $__truncated = true;


    public function useAllFixtures()
    {
        $fixturesInstance = new \Fixtures;
        $this->fixtures = $fixturesInstance->fixtures;
    }

    /**
     * Cake1.3の時だけ差し込みたい初期化処理があれば、それぞれのテストで定義する
     * 基本的には不要なはずだが、Component周りで必要になる想定
     *
     * [例]
     *   $this->Admin->controller->Lancers = new LancersComponent();
     *   これは入れ子のプロパティを設定しているので、 $this->loadComponents() では対応できない
     *   (無理に対応すると複雑になるのでやるべきではない)
     */
    protected function setUpForVer13()
    {
        // 必要に応じて子クラスで定義
    }

    /**
     * 各テスト前に走る初期化処理
     */
    function setUp()
    {
        parent::setUp();

        // 全fixturesの設定。
        if ($this->useAllFixtures) {
            $this->useAllFixtures();
        }

        // テスト間で影響が出ないようにテスト時はキャッシュを無効にしておく
        // 特定のテストで有効にしたい場合はテストメソッド内で設定を上書きする
        \Configure::write('Cache.disable', true);
        $this->setFixtures();
        $this->loadComponents();
        $this->loadModels();
        $this->loadBehaviors();
        $this->loadHelpers();
        $this->loadExceptions();
        $this->setUpForVer13();
    }

    /**
     * cake/tests/lib/cake_test_case.php のbefore($method)参照
     * 元々simpletest依存だった処理など一部不要な部分は削っている
     */
    function setFixtures()
    {
        if (isset($this->fixtures) && (!is_array($this->fixtures) || empty($this->fixtures))) {
            unset($this->fixtures);
        }

        // Set up DB connection
        if (isset($this->fixtures)) {
            $this->_initDb();
            $this->_loadFixtures();
        }

        $this->start();

        // Create records
        if (isset($this->_fixtures) && isset($this->db) && $this->__truncated && $this->autoFixtures == true) {
            foreach ($this->_fixtures as $fixture) {
                $fixture->insert($this->db);
            }
        }
    }

    /**
     * cake/tests/lib/cake_test_case.php 参照
     * 元の処理からいじっていない
     */
    function start()
    {
        if (isset($this->_fixtures) && isset($this->db)) {
            \Configure::write('Cache.disable', true);
            $cacheSources = $this->db->cacheSources;
            $this->db->cacheSources = false;
            $sources = $this->db->listSources();
            $this->db->cacheSources = $cacheSources;

            if (!$this->dropTables) {
                return;
            }
            foreach ($this->_fixtures as $fixture) {
                $table = $this->db->config['prefix'] . $fixture->table;
                if (in_array($table, $sources)) {
                    $fixture->drop($this->db);
                    $fixture->create($this->db);
                } elseif (!in_array($table, $sources)) {
                    $fixture->create($this->db);
                }
            }
        }
    }

    /**
     * cake/tests/lib/cake_test_case.php 参照
     * 元の処理からいじっていない
     */
    function end()
    {
        $this->dropFixtures();

        if (class_exists('ClassRegistry')) {
            \ClassRegistry::flush();
        }
    }

    /**
     * cake/tests/lib/cake_test_case.php 参照
     * 元の処理からいじっていない
     */
    function dropFixtures()
    {
        if (isset($this->_fixtures) && isset($this->db)) {
            if ($this->dropTables) {
                foreach (array_reverse($this->_fixtures) as $fixture) {
                    $fixture->drop($this->db);
                }
            }
            $this->db->sources(true);
            \Configure::write('Cache.disable', false);
        }
    }

    /**
     * 各テスト後に走る後処理
     *
     * cake/tests/lib/cake_test_case.php のafter($method)参照
     * 元々simpletest依存だった処理など一部不要な部分は削っている
     */
    function tearDown()
    {
        if (isset($this->_fixtures) && isset($this->db)) {
            $this->db->execute('SET FOREIGN_KEY_CHECKS = 0');
            foreach ($this->_fixtures as $fixture) {
                $fixture->truncate($this->db);
            }
            $this->db->execute('SET FOREIGN_KEY_CHECKS = 1');
            $this->__truncated = true;
        } else {
            $this->__truncated = false;
        }

        $this->end();
        parent::tearDown();
    }

    /**
     * cake/tests/lib/cake_test_case.php 参照
     * 元の処理からいじっていない
     */
    function _initDb()
    {
        $testDbAvailable = in_array('test', array_keys(\ConnectionManager::enumConnectionObjects()));

        $_prefix = null;

        if ($testDbAvailable) {
            // Try for test DB
            restore_error_handler();
            @$db =& \ConnectionManager::getDataSource('test');
            $testDbAvailable = $db->isConnected();
        }

        // Try for default DB
        if (!$testDbAvailable) {
            $db =& \ConnectionManager::getDataSource('default');
            $_prefix = $db->config['prefix'];
            $db->config['prefix'] = 'test_suite_';
        }

        \ConnectionManager::create('test_suite', $db->config);
        $db->config['prefix'] = $_prefix;

        // Get db connection
        $this->db =& \ConnectionManager::getDataSource('test_suite');
        $this->db->cacheSources  = false;

        \ClassRegistry::config(array('ds' => 'test_suite'));
    }

    /**
     * cake/tests/lib/cake_test_case.php 参照
     * 元の処理からいじっていない
     */
    function _loadFixtures()
    {
        if (!isset($this->fixtures) || empty($this->fixtures)) {
            return;
        }

        if (!is_array($this->fixtures)) {
            $this->fixtures = array_map('trim', explode(',', $this->fixtures));
        }

        $this->_fixtures = array();

        foreach ($this->fixtures as $index => $fixture) {
            $fixtureFile = null;

            if (strpos($fixture, 'core.') === 0) {
                $fixture = substr($fixture, strlen('core.'));
                foreach (\App::core('cake') as $key => $path) {
                    $fixturePaths[] = $path . 'tests' . DS . 'fixtures';
                }
            } elseif (strpos($fixture, 'app.') === 0) {
                $fixture = substr($fixture, strlen('app.'));
                $fixturePaths = array(
                    TESTS . 'fixtures',
                    VENDORS . 'tests' . DS . 'fixtures'
                );
            } elseif (strpos($fixture, 'plugin.') === 0) {
                $parts = explode('.', $fixture, 3);
                $pluginName = $parts[1];
                $fixture = $parts[2];
                $fixturePaths = array(
                    \App::pluginPath($pluginName) . 'tests' . DS . 'fixtures',
                    TESTS . 'fixtures',
                    VENDORS . 'tests' . DS . 'fixtures'
                );
            } else {
                $fixturePaths = array(
                    TESTS . 'fixtures',
                    VENDORS . 'tests' . DS . 'fixtures',
                    TEST_CAKE_CORE_INCLUDE_PATH . DS . 'cake' . DS . 'tests' . DS . 'fixtures'
                );
            }

            foreach ($fixturePaths as $path) {
                if (is_readable($path . DS . $fixture . '_fixture.php')) {
                    $fixtureFile = $path . DS . $fixture . '_fixture.php';
                    break;
                }
            }

            if (isset($fixtureFile)) {
                require_once($fixtureFile);
                $fixtureClass = \Inflector::camelize($fixture) . 'Fixture';
                $this->_fixtures[$this->fixtures[$index]] = new $fixtureClass($this->db);
                $this->_fixtureClassMap[\Inflector::camelize($fixture)] = $this->fixtures[$index];
            }
        }

        if (empty($this->_fixtures)) {
            unset($this->_fixtures);
        }
    }

    /**
     * 独自処理
     * テスト側で定義されているコンポーネントを読み込む
     */
    public function loadComponents()
    {
        foreach ($this->components as $component) {
            \App::import('Component', $component);
            $className = "{$component}Component";
            $this->$component = new $className();
        }
    }

    /**
     * 独自処理
     * テスト側で定義されているモデルを読み込む
     *
     * new直接ではなくClassRegistry::initにしているのは、元々のテストに合わせているため
     */
    public function loadModels()
    {
        foreach ($this->models as $model) {
            \App::import('Model', $model);
            $this->$model = \ClassRegistry::init($model);
            $this->$model->useDbConfig = 'test_suite';
        }
    }

    /**
     * 独自処理
     * テスト側で定義されているビヘイビアを読み込む
     *
     * newにしているのは、元々のテストに合わせているため
     */
    public function loadBehaviors()
    {
        foreach ($this->behaviors as $behavior) {
            \App::import('Behavior', $behavior);
            $className = "{$behavior}Behavior";
            $this->$className = new $className();
        }
    }

    /**
     * 独自処理
     * テスト側で定義されたHelperを読み込む
     */
    public function loadHelpers()
    {
        foreach ($this->helpers as $helper) {
            \App::import('Helper', $helper);

            // namespaceに置き換え
            $className = "\\{$helper}Helper";
            $this->$helper = new $className();
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
            \App::import('Lib', "exceptions/{$className}");
        }
    }

    /**
     * 独自処理
     * LCakeTestと互換性を持たせるための空メソッド
     * Cake1.3では何もしない
     */
    public function bindModel($model, $binds)
    {
        // 何もしない
    }
}
