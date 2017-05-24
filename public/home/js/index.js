$.extend(MARK, {
    init: function () {
        this.initModal();
    },
    initModal: function () {
        $('.modal').on('hidden.bs.modal', function (e) {
            $(this).removeData();
        }).on('loaded.bs.modal', function () {

        });
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

