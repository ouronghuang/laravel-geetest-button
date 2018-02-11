<?php

namespace Ouronghuang\GeetestButton;

use Ouronghuang\GeetestButton\Libraries\GeetestLib;

class GeetestButton
{

    /**
     * Laravel 服务容器
     *
     * @var $app
     */
    protected $app;


    /**
     * Laravel 版本
     *
     * @var $version
     */
    protected $version;

    /**
     * 是否是 Lumen
     *
     * @var $is_lumen
     */
    protected $is_lumen = false;

    /**
     * 极验应用配置
     *
     * @var $config
     */
    protected $config;

    /**
     * 极验工具库
     *
     * @var $geetestlib
     */
    protected $geetestlib;

    /**
     * Session 缓存键
     *
     * @var $session_key
     */
    protected $session_key = 'geetest';

    /**
     * 初始化
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();
        }

        $this->app = $app;
        $this->version = $app->version();
        $this->is_lumen = str_contains($this->version, 'Lumen');
        $this->config = $app['config']->get('geetest');
        $this->geetestlib = new GeetestLib($this->config['id'], $this->config['key']);
    }

    /**
     * 一次验证
     *
     * @param  void
     * @return mixed
     */
    public function startCaptchaServlet()
    {
        $data = $this->getData();

        $status = $this->geetestlib->pre_process($data, 1);

        $session = [
            $this->session_key => [
                'gtserver' => $status,
                'data' => $data,
            ],
        ];
        session($session);

        return $this->geetestlib->get_response_str();
    }

    /**
     * 二次验证
     *
     * @param  void
     * @return boolean
     */
    public function verification()
    {
        $session = session()->pull($this->session_key);

        if ($session) {

            $post = $this->getPostData();

            if ($session['gtserver'] == 1) {
                $status = $this->geetestlib->success_validate($post['geetest_challenge'], $post['geetest_validate'], $post['geetest_seccode'], $session['data']);
            } else {
                $status = $this->geetestlib->fail_validate($post['geetest_challenge'], $post['geetest_validate'], $post['geetest_seccode']);
            }

        } else {
            $status = false;
        }

        return $status;
    }

    /**
     * 检测当前用户信息
     *
     * @param  void
     * @return array
     */
    protected function getData()
    {
        return [
            'user_id' => $this->getUserId(),
            'client_type' => $this->getClientType(),
            'ip_address' => $this->getIpAddress(),
        ];
    }

    /**
     * 获取用户 id
     *
     * @param  void
     * @return integer
     */
    protected function getUserId()
    {
        return app('auth')->id() ?: 'visitor_' . str_random();
    }

    /**
     * 获取当前用户所在平台
     *
     * @param  void
     * @return string
     */
    protected function getClientType()
    {
        $agent = app('agent');

        if ($agent->isMobile()) {
            $clientType = 'h5';
        } else {
            $clientType = 'web';
        }

        return $clientType;
    }

    /**
     * 获取当前用户的 IP 地址
     *
     * @param  void
     * @return string
     */
    protected function getIpAddress()
    {
        return app('request')->getClientIp();
    }

    /**
     * 获取 POST 数据
     *
     * @param  void
     * @return array
     */
    protected function getPostData()
    {
        $allow = [
            'geetest_challenge',
            'geetest_validate',
            'geetest_seccode',
        ];

        $request = app('request');

        $data = [];

        foreach ($allow as $v) {
            $data[$v] = $request->input($v, null);
        }

        return $data;
    }
}
