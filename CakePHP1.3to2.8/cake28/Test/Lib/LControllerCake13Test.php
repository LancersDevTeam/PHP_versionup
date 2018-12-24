<?php
namespace Test\App\Lib;

/**
 * Cake1.3でもPHPUnitを実行できるように拡張したクラス
 * このクラスにはコントローラー用の処理が追加されている
 *
 * ほとんどの処理は cake/tests/lib/cake_test_case.php から持ってきている
 */
class LControllerCake13Test extends LCake13Test
{
    /**
     * cake/tests/lib/cake_test_case.php 参照
     */
    private $__savedGetData = array();

    /**
     * リクエストを通して設定された変数を保存
     * Cake2以降だと自動で設定してくれるが、Cake1系では存在しないので自前で入れている
     * @var array
     */
    private $result = array();

    public function setUp()
    {
        parent::setUp();
        $this->setServerVariables();
        // HTTPSじゃないとSecurity Componentで弾かれてテストが落ちる
        $_SERVER['HTTPS'] = 'on';
    }

    /**
     * LControllerCakeTest#callGet を参照
     */
    protected function callGet($url, $data = array())
    {
        $data['method'] = 'GET';
        $data['return'] = 'vars';
        // この呼び出しにより$this->resultに処理結果が設定されます
        $this->testAction($url, $data);
    }

    /**
     * LControllerCakeTest#assertViewAssigns を参照
     */
    protected function assertViewAssigns($assigns)
    {
        foreach ($assigns as $key => $expected) {
            $actual = $this->getViewAssign($key);
            $this->assertSame($expected, $actual, sprintf("View変数 %s の値が期待値と違います。", $key));
        }
    }

    /**
     * LControllerCakeTest#getViewAssign を参照
     */
    protected function getViewAssign($key)
    {
        if (isset($this->result[$key])) {
            return $this->result[$key];
        }
        $this->fail(sprintf("View変数 %s は存在しません。設定漏れかtypoを確認してください。", $key));
    }

    /**
     * LControllerCakeTest#assertMissingRouteFromGet を参照
     */
    protected function assertMissingRouteFromGet($url, $message = '')
    {
        // LControllerCakeTestとの互換性のための空メソッド
        // 何も検証しない(できない)
        // Cake1の場合はcake/console/errorのmissingControllerが動き、例外を出さずにexitしてしまう
    }

    protected function assertResponseCode($code)
    {
        // LControllerCakeTestとの互換性のための空メソッド
        // 何も検証しない(できない)
    }

    protected function assertTemplate($name)
    {
        // LControllerCakeTestとの互換性のための空メソッド
        // 何も検証しない(できない)
    }

    /**
     * LControllerCakeTest#assertRedirectContains を参照
     */
    protected function assertRedirectContains($controller, $requestUrl, $redirectUrl)
    {
        // LControllerCakeTestとの互換性のための空メソッド
        // 何も検証しない(できない)
    }

    /**
     * LControllerCakeTest#setServerVariables を参照
     */
    private function setServerVariables()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Chrome 36 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36';
    }

    /**
     * $this->testAction()を実行するためのマジックメソッド
     * そのままtestAction()という名前で定義すると、test始まりのためphpunitがテスト対象と勘違いして実行してしまう
     * Cake2系ではマジックメソッドで対応しているので、同じような処理にしている
     */
    public function __call($name, $arguments)
    {
        if ($name === 'testAction') {
            $result = call_user_func_array(array($this, '_testAction'), $arguments);
            $this->result = $result;
            return $result;
        }
        throw new \BadMethodCallException("Method '{$name}' does not exist.");
    }

    /**
     * cake/tests/lib/cake_test_case.php 参照
     * 処理の本筋は何もいじっていない
     * (Dispatcherのクラスとか参照(バックスラッシュ)とかはいじっている)
     */
    protected function _testAction($url, $params = array())
    {
        $default = array(
            'return' => 'result',
            'fixturize' => false,
            'data' => array(),
            'method' => 'post',
            'connection' => 'default'
        );

        if (is_string($params)) {
            $params = array('return' => $params);
        }
        $params = array_merge($default, $params);

        $toSave = array(
            'case' => null,
            'group' => null,
            'app' => null,
            'output' => null,
            'show' => null,
            'plugin' => null
        );
        $this->__savedGetData = (empty($this->__savedGetData))
            ? array_intersect_key($_GET, $toSave)
            : $this->__savedGetData;

        $data = (!empty($params['data'])) ? $params['data'] : array();

        if (strtolower($params['method']) == 'get') {
            $_GET = array_merge($this->__savedGetData, $data);
            $_POST = array();
        } else {
            $_POST = array('data' => $data);
            $_GET = $this->__savedGetData;
        }

        $return = $params['return'];
        $params = array_diff_key($params, array('data' => null, 'method' => null, 'return' => null));

        $dispatcher = new LCake13TestDispatcher();

        if ($return != 'result') {
            if ($return != 'contents') {
                $params['layout'] = false;
            }

            ob_start();
            @$dispatcher->dispatch($url, $params);
            $result = ob_get_clean();

            if ($return == 'vars') {
                $view = \ClassRegistry::getObject('view');
                $viewVars = $view->getVars();

                $result = array();

                foreach ($viewVars as $var) {
                    $result[$var] = $view->getVar($var);
                }

                if (!empty($view->pageTitle)) {
                    $result = array_merge($result, array('title' => $view->pageTitle));
                }
            }
        } else {
            $params['return'] = 1;
            $params['bare'] = 1;
            $params['requested'] = 1;

            $result = @$dispatcher->dispatch($url, $params);
        }

        if (isset($this->_actionFixtures)) {
            unset($this->_actionFixtures);
        }
        \ClassRegistry::flush();

        return $result;
    }
}

/**
 * cake/tests/lib/cake_test_case.php 参照
 */
if (!class_exists('dispatcher')) {
    require CAKE . 'dispatcher.php';
}

/**
 * テスト用のDispatcher
 * cake/tests/lib/cake_test_case.php 参照
 */
class LCake13TestDispatcher extends \Dispatcher
{
    function _invoke(&$controller, $params, $missingAction = false)
    {
        if (array_key_exists('layout', $params)) {
            $controller->layout = $params['layout'];
        }
        return parent::_invoke($controller, $params, $missingAction);
    }
}
