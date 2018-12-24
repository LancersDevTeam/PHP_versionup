<?php
namespace L;

use \Letto\Config\Config;

class Cake
{
    /**
     * 切り替え済みかどうか
     * - true: 切り替え済み(cakephp28)
     * - false: まだ(cakephp13)
     *
     * @return boolean
     */
    public static function isSwitched()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $list = static::loadConfig()->get('switch.switched.cakephp28', array());
        return static::_isSwitched($uri, $list);
    }

    /**
     * isSwitched()の処理本体
     *
     * @param $requestUrl
     * @param $migrateUrls
     * @return boolean
     */
    private static function _isSwitched($requestUrl, $migrateUrls)
    {
        // クエリパラメータを考慮するためスラッシュに置換
        $formatUrl = str_replace(array('?', '#'), '/', $requestUrl);
        $params = explode('/', $formatUrl);

        if (in_array($params[1], $migrateUrls)) {
            return true;
        }

        // クエリパラメータを省いたURL
        $urlAndParameter = explode('?', $requestUrl);
        $baseUrl = strtolower($urlAndParameter[0]);

        // パスの完全一致

        if (in_array($baseUrl, $migrateUrls)) {
            return true;
        }

        // アスタリスクがある場合の一致

        foreach ($migrateUrls as $v) {
            $v = strtolower($v);
            if (strpos($v, '*') === false) {
                continue;
            }
            // 例: /admin/faqs/* => /admin/faqs/.*
            $regex = str_replace('*', '.*', $v);
            if (preg_match("|{$regex}|", $baseUrl)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 切り替えの情報を取得する
     *
     * @return object $config
     */
    private static function loadConfig()
    {
        $config = new Config(ROOT . '/app/config/Letto');
        $config->load('switch', 'yml');
        return $config;
    }
}
