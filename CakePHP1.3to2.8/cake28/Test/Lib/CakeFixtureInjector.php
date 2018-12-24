<?php
namespace Test\App\Lib;

/**
 * AllTestsTestのようにCakeTestSuite経由で実行するとfixtureManagerが設定されないため、自前で設定するためのリスナー
 * TODO: Cake3系になると同じようなリスナーが用意されているので、そちらを使えば恐らくこのクラスは不要
 *
 * @link https://github.com/cakephp/cakephp/blob/3f9db2b/phpunit.xml.dist#L28-L34
 * @package Test\App\Lib
 */
class CakeFixtureInjector extends \PHPUnit_Framework_BaseTestListener
{
    /**
     * @var \CakeFixtureManager
     */
    private $_fixtureManager;

    /**
     * テストスイートを保持するための変数
     *
     * @var \PHPUnit_Framework_TestSuite
     */
    private $_first;

    public function __construct()
    {
        // cake1系ではAllTestを流すこともないので、この処理はcake2だけ必要。
        // 条件文を入れておかないとcake1のテスト実行時に落ちる
        if (method_exists('\App', 'uses')) {
            \App::uses('CakeFixtureManager', 'TestSuite/Fixture');
            $this->_fixtureManager = new \CakeFixtureManager();
        }
    }

    /**
     * CakeTestCase#runは次のような処理順のため、リスナーではfixtureManagerの設定とloadだけ行いunloadは行わない。
     *
     * 1. $test->fixtureManagerがあればload
     *   - この時点ではnullなのでloadされない
     * 2. PHPUnit_Framework_TestCase#runを実行
     *   2.1. リスナーのstartTestを実行
     *     - ここで$test->fixureManagerを設定し、テストデータをloadする
     *   2.2. テストケースの本体を実行
     *   2.3. リスナーのendTestを実行
     *     - 何もしない
     * 3. $test->fixtureManagerがあればunload
     *
     */


    /**
     * テストスイート(テストファイル)で最初の1回だけ実行される処理
     *
     * @param \PHPUnit_Framework_TestSuite $suite
     * @return void
     */
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        // 各テストケースで使うためにキャッシュする
        if (empty($this->_first)) {
            $this->_first = $suite;
        }
    }

    /**
     * Adds fixtures to a test case when it starts.
     *
     * @param \PHPUnit_Framework_Test $test
     * @return void
     */
    public function startTest(\PHPUnit_Framework_Test $test)
    {
        // phpunitを直接実行するテストの場合はCakeライブラリを介さず、fixtureManagerがプロパティに存在しないので何もしない
        // see #15620
        if (!property_exists($test, 'fixtureManager')) {
            return;
        }
        // 個別でテストを流した場合は既にfixtureManagerが設定されているので何もしない
        if (is_null($test->fixtureManager) && !is_null($this->_fixtureManager)) {
            $test->fixtureManager = $this->_fixtureManager;

            $this->_fixtureManager->fixturize($test);
            $this->_fixtureManager->load($test);
        }
    }
}
