<?php
namespace L\Cake;

class Request
{
    protected static $keys = array(
        'data',
        'query',
        'url',
        'base',
        'webroot',
        'here',
        'params',
        'action',
    );

    /**
     * cakePHP1.3にrequestを生やして2.8との差分を減らす
     * 参照受取をすることで動的に追加する
     *
     * @param   object  $self - $controller or $view object
     * @return  void
     */
    public static function set(&$self)
    {
        $self->request = new \stdClass;
        foreach (static::$keys as $name) {
            switch ($name) {
                case 'base':
                case 'here':
                case 'webroot':
                case 'data':
                case 'params':
                    $self->request->{$name} = $self->{$name};
                    break;
                case 'action':
                    $self->request->{$name} = $self->params['action'];
                    break;
                case 'query':
                    $self->request->{$name} = $self->params['url'];
                    break;
                case 'url':
                    $self->request->{$name} = $self->params['url']['url'];
                    break;
            }
        }
    }
}
