cake13とcake28でAuthComponentの挙動が違う。ポイントは次の2つのメソッド。

- `Auth#login()`
    - 結果的にセッションに書き込まれる項目の数が違う
- `Auth#user()`
    - 取得できるデータ構造が違う

| | cake13 | cake28 |
|---|----|---|
| `Auth#login()` | 引数で渡したデータ以外に`User`モデルの情報を保存する(nicknameとかidとか) | 引数で渡したデータだけを保存する |
| `Auth#user()` | `array('User' => $data)`の構造を返す | `login()`で保存した$dataのまま返す |

## 前提 - cake13の挙動

ログインはcake13(`UserController#login()`)。
上記に従い、cake13の`app_controller`では`User`アクセスしてデータを取得している。

```
$this->loginUser = $authUser[$this->Auth->userModel]
```

cake28の`AppController`では`Auth->user()`の戻り値をそのまま使っている。

```
$this->loginUser = $authUser
```

ここまでは挙動として問題ない。

## 要修正1 - 必要な値が足りない

冒頭の表に書いた通り、cake28では`login()`に与えたデータしかセッションに保存しない。
なので現状では`email`と`password`しか入らない。

```
$loginData['User']['email'] = $this->data["User"]["email"]; 
$loginData['User']['password'] = Security::hash($this->data['User']['password'], 'sha256', true); 
if ($this->Auth->login($loginData)) {
```

`nickname`やら`id`やら他の情報が諸々必要になる。

## 要修正2 - 保存するデータ構造

```
$loginData['User']['email'] = $this->data["User"]["email"];
$loginData['User']['password'] = Security::hash($this->data['User']['password'], 'sha256', true);
if ($this->Auth->login($loginData)) {
```

↓

```
$loginData['email'] = $this->data["User"]["email"];
$loginData['password'] = Security::hash($this->data['User']['password'], 'sha256', true);
if ($this->Auth->login($loginData)) {
```

`['User']`を消す。このあとの参照も修正する。

```
$authUser[$this->Auth->userModel]['id']
```

↓

```
$authUser['id']
```

## 要修正というか確認 - セッションに書き込まれない？

`Auth#login()`の中で`$this->Session->write(static::$sessionKey, $user);`まで動いたにも関わらず、次のリクエストが来た時にセッションに存在しない。
(`sessionKey`が同じことは確認した。key以前に`$_SESSION`に存在していなかった)

原因は未調査。
