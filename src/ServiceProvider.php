<?php

namespace Ouronghuang\GeetestButton;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * 注册服务
     *
     * @param  void
     * @return void
     */
    public function register()
    {
        $this->configure();
        $this->offerPublishing();
        $this->registerServices();
    }

    /**
     * 合并配置信息
     *
     * @param  void
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/geetest.php', 'geetest'
        );
    }

    /**
     * 发布配置信息
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/geetest.php' => config_path('geetest.php'),
            ], 'geetest-config');
        }
    }

    /**
     * 设置需要注册的服务
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->singleton(GeetestButton::class, function () {
            return new GeetestButton($this->app);
        });
    }

    /**
     * 引导服务
     *
     * @param  void
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->defineAssetPublishing();
        $this->validatorExtension();
    }

    /**
     * 注册视图
     *
     * @param  void
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('geetest.prefix', 'geetest'),
            'as' => config('geetest.as', 'geetest'),
            'namespace' => 'Ouronghuang\GeetestButton\Http\Controllers',
            'middleware' => config('geetest.middleware', ['web']),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * 发布前端资源文件
     *
     * @param  void
     * @return void
     */
    protected function defineAssetPublishing()
    {
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/geetest'),
        ], 'geetest-assets');
    }

    /**
     * 扩展验证规则
     *
     * @param  void
     * @return void
     */
    protected function validatorExtension()
    {
        $this->app['validator']->extend(config('geetest.captcha', 'captcha'), function ($attribute, $value, $parameters) {
            return app(GeetestButton::class)->verification();
        });
    }
}
