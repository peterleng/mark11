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
    addFavorite:function () {
        var url = 'http://mark11.cn';
        var title = 'Mark11 自己专属的导航站';
        if (document.all)
        {
            window.external.addFavorite(url,title);
        }
        else if (window.sidebar)
        {
            window.sidebar.addPanel(title, url, "");
        }else{
            alert("您的浏览器不支持,请按 Ctrl+D 手动收藏!");
        }
    },
    setHome:function (obj) {
        var url = 'http://mark11.cn';
        try{
            obj.style.behavior='url(#default#homepage)';
            obj.setHomePage(url);
        }
        catch(e){
            if(window.netscape) {
                try {
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                }
                catch (e) {
                    alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                }
                var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                prefs.setCharPref('browser.startup.homepage',url);
            }
        }
    }
});

$(document).ready(function () {
    MARK.init();
});

