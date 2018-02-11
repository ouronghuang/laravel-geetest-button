## Laravel Geetest Button

此扩展包是针对 [极验证](http://www.geetest.com/) 的行为验证 v3.0.0 开发，适用于 Laravel 5.5.* 版本。

## 索引

* [快速开始](#快速开始)
* [发布文件说明](#发布文件说明)
* [依赖](#依赖)
* [许可证](#许可证)

## 快速开始

#### 1. 安装 laravel-geetest-button

```Shell
    composer require ouronghuang/laravel-geetest-button
```

#### 2. 发布配置文件与前端资源文件

```Shell
    php artisan vendor:publish --provider="Ouronghuang\GeetestButton\ServiceProvider"
```

#### 3. 请在 `.env` 和 `.env.example` 文件添加以下配置

```env
    .
    .
    .
    GEETEST_ID=
    GEETEST_KEY=
```

注意：id 与 key 请在 [极验后台](https://account.geetest.com) 获取。

#### 4. 在要需要验证的页面引入以下文件

```HTML
    .
    .
    .
    <link href="{{ asset('vendor/geetest/css/gt.css') }}" rel="stylesheet"/>
    .
    .
    .
    <script src="{{ asset('vendor/geetest/js/gt.js') }}"></script>
    <script src="{{ asset('vendor/geetest/js/geetest.js') }}"></script>
    .
    .
    .   
```

注意：

* 需要引入 `jQuery`；
* `gt.js` 必须在 `geetest.js` 之前引入。

#### 5. 在相应的表单加入以下代码

```HTML
    .
    .
    .
    <div class="form-group geetest-captcha">
        <label for="captcha">行为验证</label>
        <div id="embed-captcha"></div>
        <div class="wait">
            正在加载验证码
            <i class="fa fa-spin fa-spinner" aria-hidden="true"></i>
        </div>
        <div class="notice hide text-danger">
            <i class="fa fa-times-circle" aria-hidden="true"></i>
            请先完成验证
        </div>
        <input type="hidden" id="captcha" name="captcha">
    </div>
    .
    .
    .
```

注意：

* 上述示例排版采用 [bootstrap@4.0.0](https://www.npmjs.com/package/bootstrap)；
* 上述示例图标采用 [font-awesome@4.7.0](https://www.npmjs.com/package/font-awesome)。

也可以使用如下基本结构

```HTML
    .
    .
    .
    <div class="geetest-captcha">
        <label for="captcha">行为验证</label>
        <div id="embed-captcha"></div>
        <div class="wait">正在加载验证码...</div>
        <div class="notice hide text-danger">请先完成验证</div>
        <input type="hidden" id="captcha" name="captcha">
    </div>
    .
    .
    .
```

注意：

* 请保持如上结构；
* 样式可以根据需要调整;
* 加入 `<input type="hidden" id="captcha" name="captcha">` 是便于二次验证。

#### 6. 在服务端表单验证中加入以下规则

```PHP
    .
    .
    .
    $this->validate($request, [
        .
        .
        .
        'captcha' => 'captcha',
    ]);
    .
    .
    .
```

至此，安装完成。

## 发布文件说明

#### 1. 配置文件 `config/geetest.php`

| 配置 | 描述 | 数据类型 | 配置值 |
| --- | --- | --- | --- |
| `id` | 对应验证的 `id` | `string` | 请在 [极验后台](https://account.geetest.com) 获取 |
| `key` | 对应验证的 `key` | `string` | 请在 [极验后台](https://account.geetest.com) 获取 |
| `prefix` | 路由前缀 | `string` | 默认：`''`，如果与其它包冲突则可以修改此项 |
| `as` | 路由别名前缀 | `string` | 默认：`'geetest'`，一般不需要改动 |
| `middleware` | 路由中间件 | `array` | 默认：`['web']`，一般不需要改动 |
| `captcha` | 表单验证规则名称 | `string` | 默认：`'captcha'`，如果与其它包冲突则可以修改此项 |

说明：

* 如果修改了 `prefix` 的值为 `test`，则需要修改 `public/vendor/geetest/js/geetest.js` 的

```JavaScript
    .
    .
    .
    $.ajax({
        url: '/captcha?t=' + (new Date()).getTime(),
        .
        .
        .
    });
```

为

```JavaScript
    .
    .
    .
    $.ajax({
        url: '/test/captcha?t=' + (new Date()).getTime(),
        .
        .
        .
    });
```

* 如果修改了 `captcha` 的值为 `test`，则需要修改相应的表单验证规则

```PHP
    .
    .
    .
    $this->validate($request, [
        .
        .
        .
        'captcha' => 'captcha',
    ]);
    .
    .
    .
```

为

```PHP
    .
    .
    .
    $this->validate($request, [
        .
        .
        .
        'captcha' => 'test',
    ]);
    .
    .
    .
```

#### 2. 前端资源文件

| 文件名 | 描述 | 文件位置 |
| --- | --- | --- |
| `gt.css` | 验证码样式文件，可根据需要修改 | `public/vendor/geetest/css/gt.css` |
| `gt.js` | 验证码库文件，一般不需要修改 | `public/vendor/geetest/js/gt.js` |
| `geetest.js` | 验证码执行文件，可根据需要修改 | `public/vendor/geetest/js/geetest.js` |

## 依赖

* Laravel 5.5.*
* jQuery 1.9.1+

## 许可证

遵循 [MIT](./LICENSE) 开源许可
