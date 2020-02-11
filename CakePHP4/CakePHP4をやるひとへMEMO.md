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

## テンプレートファイルの拡張子が ctpからphpになった。
