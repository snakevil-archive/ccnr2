<?php
/**
 * 定义抽象控制器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   GPL-3.0+
 * @license   CC-BY-NC-ND-3.0
 */

namespace snakevil\ccnr2\Component;

use Zen\Core as ZenCore;
use Zen\View as ZenView;
use Zen\Web\Application as ZenApp;

/**
 * 抽象控制器组件。
 *
 * @package snakevil\ccnr2
 * @version 2.0.0
 * @since   2.0.0
 */
abstract class Controller extends ZenCore\Application\Controller\Controller
{
    /**
     * 派发令牌实例。
     *
     * @var ZenCore\Application\IRouterToken
     */
    protected $token;

    /**
     * {@inheritdoc}
     *
     * @param  ZenCore\Application\IRouterToken $token 派发令牌
     * @return void
     */
    final public function act(ZenCore\Application\IRouterToken $token)
    {
        $this->token = $token;
        try {
            $o_view = 'POST' == $this->input['server:REQUEST_METHOD']
                ? $this->onPost()
                : $this->onGet();
        } catch (\Exception $ee) {
            $o_view = $this->onError($ee);
        }
        if ($o_view instanceof ZenView\IView) {
            $s_out = $o_view->render();
            $this->output->write($s_out);
        }
        if (isset($this->input['server:HTTP_ACCEPT_ENCODING'])) {
            $this->output->header('Vary', 'Accept-Encoding');
        }
        $this->output
            ->header('X-Cache', 'MISS')
            ->header('X-Powered-By', 'CCNRv2')
            ->close();
    }

    /**
     * HTTP POST 请求事件。
     *
     * @return ZenView\IView|void
     *
     * @throws ExMethodNotAllowed 当 POST 方法不支持时
     */
    protected function onPost()
    {
        throw new ExMethodNotAllowed;
    }

    /**
     * HTTP GET 请求事件。
     *
     * @return ZenView\IView|void
     *
     * @throws ExMethodNotAllowed 当 GET 方法不支持时
     */
    protected function onGet()
    {
        throw new ExMethodNotAllowed;
    }

    /**
     * 异常容错事件。
     *
     * @param  \Exception         $ee 捕获地异常
     * @return ZenView\IView|void
     */
    protected function onError(\Exception $ee)
    {
        if ($ee instanceof ExMethodNotAllowed) {
            $this->output->state(ZenApp\IResponse::STATUS_METHOD_NOT_ALLOWED);
        }

        var_dump($ee);

        return;
    }

    /**
     * 缓存指定视图。
     *
     * @param  ZenView\IView         $view  待缓存地视图
     * @param  string                $path  缓存文件路径
     * @param  ZenCore\Type\DateTime $mtime 可选。指定修改时间
     * @return self
     */
    protected function cache(ZenView\IView $view, $path, ZenCore\Type\DateTime $mtime = null)
    {
        if (!$this->inDev()) {
            $p_path = 'var/cache/' . $path;
            $p_dir = dirname($p_path);
            if (!is_dir($p_dir) && !mkdir($p_dir, 0755, true) || !file_put_contents($p_path, $view)) {
                throw new ExCachingDenied($path);
            }
            if (null !== $mtime) {
                touch($p_path, $mtime->getTimestamp());
            }
        }

        return $this;
    }

    /**
     * 判断是否为开发模式。
     *
     * @return bool
     */
    protected function inDev()
    {
        return file_exists('@DEV');
    }
}
