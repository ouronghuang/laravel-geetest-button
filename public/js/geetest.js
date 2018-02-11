var handlerEmbed = function (captchaObj) {
    var item = $('.geetest-captcha');
    var wait = item.children('.wait');
    var notice = item.children('.notice');

    $('[type="submit"]').click(function (e) {
        var validate = captchaObj.getValidate();
        if (!validate) {
            notice.removeClass('hide').addClass('show');
            setTimeout(function () {
                notice.removeClass('show').addClass('hide');
            }, 2000);
            e.preventDefault();
        }
    });

    captchaObj.appendTo('#embed-captcha');
    captchaObj.onReady(function () {
        wait.addClass('hide');
    });
};

$.ajax({
    url: '/captcha?t=' + (new Date()).getTime(),
    type: 'get',
    dataType: 'json',
    success: function (data) {
        initGeetest({
            gt: data.gt,
            challenge: data.challenge,
            new_captcha: data.new_captcha,
            product: 'embed',
            offline: !data.success,
            width: '100%'
        }, handlerEmbed);
    }
});
