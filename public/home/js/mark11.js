$.extend(MARK, {
    init: function () {
        this.initModal();
        this.initToastr();
    },
    initModal: function () {
        $('.modal').on('hidden.bs.modal', function (e) {
            $(this).removeData();
        }).on('loaded.bs.modal', function () {
            var form = $(this).find('form');

            $(this).find(".modal-footer button[type='submit']").on('click', function () {
                form.submit();
            });
        });
    },
    initToastr: function () {
        if (!toastr) return;
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'positionClass': 'toast-top-center',
            'onclick': null,
            'showDuration': 200,
            'hideDuration': 800,
            'timeOut': 3000,
            'extendedTimeOut': 800,
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
            'onHidden': function (response) {
                if(response.map.type == 'success'){
                    location.href = location.href;
                }
            },
            'onCloseClick': null
        };
        MARK.toastr = toastr;
    },
    formVerify: {
        /**
         * 指定不验证的情况
         */
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        /**
         * 指定验证后验证字段的提示字体图标。（默认是bootstrap风格）
         */
        feedbackIcons: true,
        /**
         * 生效规则（三选一）
         * enabled 字段值有变化就触发验证
         * disabled,submitted 当点击提交时验证并展示错误信息
         */
        live: 'enabled',
        /**
         * 为每个字段指定通用错误提示语
         */
        message: '填写内容无效',
        /**
         * 指定提交的按钮，例如：'.submitBtn' '#submitBtn'
         * 当表单验证不通过时，该按钮为disabled
         */
        submitButtons: '.submit',
        /**
         * submitHandler: function(validator, form, submitButton) {
             *   //validator: 表单验证实例对象
             *   //form  jq对象  指定表单对象
             *   //submitButton  jq对象  指定提交按钮的对象
             * }
         * 在ajax提交表单时很实用
         *   submitHandler: function(validator, form, submitButton) {
                    // 实用ajax提交表单
                    $.post(form.attr('action'), form.serialize(), function(result) {
                        // .自定义回调逻辑
                    }, 'json');
                 }
         *
         */
        submitHandler: null,
        /**
         * 为每个字段设置统一触发验证方式（也可在fields中为每个字段单独定义），默认是live配置的方式，数据改变就改变
         * 也可以指定一个或多个（多个空格隔开） 'focus blur keyup'
         */
        trigger: null,
        /**
         * Number类型  为每个字段设置统一的开始验证情况，当输入字符大于等于设置的数值后才实时触发验证
         */
        threshold: null,
        fields: null
    },
    //收藏网址
    addFavorite: function (url, title) {
        try {
            window.external.addFavorite(url, title);
        }
        catch (e) {
            try {
                window.sidebar.addPanel(title, url, "");
            }
            catch (e) {
                alert("加入收藏失败，请使用Ctrl+D进行添加");
            }
        }
    },
    //设为首页
    setHome: function (obj, url) {
        try {
            obj.style.behavior = 'url(#default#homepage)';
            obj.setHomePage(url);
        }
        catch (e) {
            if (window.netscape) {
                try {
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                }
                catch (e) {
                    alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                }
                var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                prefs.setCharPref('browser.startup.homepage', url);
            } else {
                alert('您的浏览器不支持自动自动设置首页, 请使用浏览器菜单手动设置!');
            }
        }
    },
    //保存到桌面
    toDesktop: function (sUrl, sName) {
        try {
            var WshShell = new ActiveXObject("WScript.Shell");
            var oUrlLink = WshShell.CreateShortcut(WshShell.SpecialFolders("Desktop") + "//" + sName + ".url");
            oUrlLink.TargetPath = sUrl;
            oUrlLink.Save();
        }
        catch (e) {
            alert("当前IE安全级别不允许操作！请设置后在操作.");
        }
    }
});

$(document).ready(function () {
    MARK.init();
});

