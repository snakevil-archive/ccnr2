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
        $this->output->close();
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
}
