<?php

namespace Ouronghuang\GeetestButton\Http\Controllers;

use GeetestButton;

class CaptchaController extends Controller
{
    /**
     * 获取验证码
     *
     * @param  void
     * @return array
     */
    public function captcha()
    {
        return GeetestButton::startCaptchaServlet();
    }
}
