<?php
namespace Test\App\Lib;

/**
 * Controllerテスト用の共通クラス。
 * このクラスは以下の責務を持つ。
 *
 *   1. Controller用テストの共通処理を持たせる
 *   2. Cake2.x -> Cake3.xへのテストコード移行をなるべく簡単に済ませる
 *      リクエスト呼び出しの部分やassert周りなど、書き方が変わる部分はこのクラスで吸収する
 *
 * @package Test\App\Lib
 */
class LControllerCakeTest extends LCakeTest
{
    public function setUp()
    {
        parent::setUp();
        $this->disableAutoMock();
        $this->setServerVariables();
        $this->requireClass();
    }

    /**
     * GETリクエストを投げます。
     *
     * @param string $url リクエストURL(例: /award)
     * @param array $data https://book.cakephp.org/2.0/ja/development/testing.html#get 参照
     * @return mixed
     */
    protected function callGet($url, $data = array())
    {
        $data['method'] = 'GET';
        // この呼び出しにより$this->varsなどに処理結果が設定されます
        $this->testAction($url, $data);
    }

    /**
     * リクエスト後のView変数に、指定した値が設定されているか検証します。
     *
     * @param array $assigns 期待する変数名と値の配列
     */
    protected function assertViewAssigns($assigns)
    {
        foreach ($assigns as $key => $expected) {
            $actual = $this->getViewAssign($key);
            $this->assertSame($expected, $actual, sprintf("View変数 %s の値が期待値と違います。", $key));
        }
    }

    /**
     * 指定したView変数を取得します。
     *
     * @param $key 取得するView変数のキー
     * @return mixed 指定したView変数の値
     */
    protected function getViewAssign($key)
    {
        if (isset($this->vars[$key])) {
            return $this->vars[$key];
        }
        $this->fail(sprintf("View変数 %s は存在しません。設定漏れかtypoを確認してください。", $key));
    }

    /**
     * GETでのルーティングが正しくないことを検証します。
     *
     * @param string $url リクエストURL
     * @param string $message テストエラー時のメッセージ
     */
    protected function assertMissingRouteFromGet($url, $message = '')
    {
        $data = array('method' => 'GET');
        // cakeのバージョンアップで挙動が変わる可能性があるため、phpunitの@expectedExceptionではなくtry-catchでテストしている
        try {
            $this->testAction($url, $data);
        } catch (\MissingActionException $e) {
            // 厳密にどのパスルールに引っかかったのか、までは見れていない
            $this->assertTrue(true);
            return;
        } catch (\Exception $e) {
            $this->fail(sprintf("[%s] 期待したものではない例外が検出されました。\n[%s]", $message, $e->getMessage()));
            return;
        }
        $this->fail(sprintf("[%s] エラーになることを期待しましたが、処理は正常に終了しました。", $message));
    }
    /**
     * レスポンスのステータスコードを検証します。
     *
     * @param int $code ステータスコード
     */
    protected function assertResponseCode($code)
    {
        // TODO Cake2.x系には存在しないが、Cake3.x系になるとassertResponseCodeが使えるようになる
        //      Cake3.xでIntegrationTestCaseに移行したら、このメソッドを消すだけでOKなはず
        //      (IntegrationTestCaseのassertResponseCode()が使われてテストが通るはず)
    }

    /**
     * 適用されたテンプレートを検証します。
     *
     * @param string $name テンプレート名。例: Users/index
     */
    protected function assertTemplate($name)
    {
        // TODO Cake2.x系には存在しないが、Cake3.x系になるとassertTemplateが使えるようになる
        //      Cake3.xでIntegrationTestCaseに移行したら、このメソッドを消すだけでOKなはず
        //      (IntegrationTestCaseのassertTemplate()が使われてテストが通るはず)
    }

    /**
     * リダイレクトが正しく行われているか検証します。
     *
     * @param string $controller `Controller`を除いたコントローラー名(例: Award)
     * @param string $requestUrl リクエストURL(例: /award)
     * @param string $redirectUrl 部分一致に使うリダイレクト先のURL(例: /award/2017)
     */
    protected function assertRedirectContains($controller, $requestUrl, $redirectUrl)
    {
        // TODO Cake2.x系には存在しないが、Cake3.x系になるとassertRedirectContainsが使えるようになる
        //      このクラスのrequireClass()のAppController周りも同時に消す

        // Cake2.xのテストではredirect()で処理が止まらず、その後も実行されてしまう。
        // 都度returnを書いていくのは手間だし動作確認も必要になる。
        // そのため、ここでは意図的にredirect()時に例外を発生させてテストしている
        // 参考: http://blog.howtelevision.co.jp/entry/2014/07/25/151754
        $errorMessage = 'called redirect';
        try {
            // 指定したコントローラーのredirectをモックにする
            $this->generate($controller, array(
                'methods' => array('redirect'),
            ));
            // redirectが呼ばれたら例外を発生させる
            $this->controller->expects($this->any())
                ->method('redirect')
                ->with($redirectUrl)
                ->will($this->throwException(new \Exception($errorMessage)));

            $this->callGet($requestUrl);
        } catch (\Exception $e) {
            $this->assertSame($errorMessage, $e->getMessage());
            return;
        }
        $this->fail(sprintf("[%s] リダイレクトが発生しませんでした。", $requestUrl));
    }

    /**
     * コントローラーをモックにしない。
     * 個別のテスト内でtrueにしてもsetUpで毎回戻すためにメソッドにしている。
     */
    private function disableAutoMock()
    {
        $this->autoMock = false;
    }

    /**
     * $_SERVER 変数に必要な値を設定する。
     */
    private function setServerVariables()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Chrome 36 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36';
    }

    /**
     * テスト環境の都合で仕方なく直接クラスを読み込む。
     * あくまで「テスト環境の都合」に関係するものだけにする。
     *
     * 実コードを修正すべきようなものは実コードを修正すること。
     */
    private function requireClass()
    {
        // TODO Cake2.xにおける暫定対応なので、Cake3.x系になったら不要。assertRedirectContains()と共に消す
        // 以下の理由による
        //   - Cake2.xではredirect()があるとコントローラーのテストが失敗する
        //   - 対応としては以下の2つを取るのが一般的な模様
        //     - コントローラーはモックにする
        //     - リクエスト後のheadersにあるLocationを見て判断する
        //   - コントローラーをモックにすると、呼び出し時に「AppController」が解決できずにエラーになる
        \App::uses('AppController', 'Controller');
    }
}
