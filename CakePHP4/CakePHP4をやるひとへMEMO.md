
参考：CakePHP3→CakePHP4にmigrateしてみた
https://engineer.blog.lancers.jp/2019/12/cakephp4_admin/

## PHP 7.2以上が必要

- CakePHP2.10はPHP7.2対応
  - https://engineer.blog.lancers.jp/2019/05/finish_php73/
- CakePHP3はPHP7.2対応
- CakePHP4はPHP7.2以上が必要

## PHPUnitは8.5以上

PHPUnitを使用するならPHPUnit8.5以上をインストール必要があります。

- CakePHP2はPHPUnit5.7まで対応
- CakePHP3はPHPUnit6.5まで対応
- CakePHP4はPHPUnit8.5以上が必要

## 継承メソッドは戻り値を明示的に書かないとコケる
```
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }
```
```
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
    }
```

## データ作成時のnewEntityメソッドの挙動が変わった

NG newEntityとpatchEntityの組み合わせだった。
```
$article = $this->Articles->newEntity();
$article = $this->Articles->patchEntity($article, $this->request->getData());
```
OK newEntityだけで作れる
```
$article = $this->Articles->newEntity($this->request->getData());
```

## テンプレートファイルについて

### ディレクトリの位置が変更された

src/Template
↓
template

## 拡張子が ctpからphpになった

## その他

\Cake\Database\Driver
↓
\Cake\Database\DriverInterface
