new VConsole();

Vue.use(VueLazyload, {
    preLoad: 1.8,
    error: './src/card.jpg',
    loading: './src/loading.gif',
    attempt: 1,
    listenEvents: ['scroll']
})

var app = new Vue({
    el: '#app',
    data: { //大P开头，表示请求数据
        Ptype: '',
        Pkey: '',
        PmodeRank: '',
        PmodeSearch: '',
        Ppage: 1,
        Pid: '16361124',
        Porder: '',
        Pdate: '',
        Purl: '',
        Pquality: 'medium',
        PresponseA: '',
        PresponseB: '',
        PresponseC: '',
        Perror: '',
        mdui: '',
        localStar: [],
        localHistory: [],
        picked: 'planB',
        isPreview: false,
        isDisabled: true,
        showHotTags: false,
        showDetailTags: false,
        showDetailMsg: false,
    },

    methods: {
        parseApi: function (type) {
                //错误处理
                if (app.Ppage < 1) {
                    app.Ppage = 1;
                    return false;
                }
                app.showHotTags = false;
                var date = '';
                var typeModeA = ['member_illust', 'favorite', 'related', 'rank', 'search'];
                var typeModeB = ['following', 'follower'];
                //判断输入type
                switch (type) {
                case "typeA":
                    //若查询数组a
                    if (!app.Ptype.length) {
                        mdui.alert("必须指定type!");
                        return false
                    }
                    if (typeModeB.indexOf(app.Ptype) === -1) app.Ppage = 1;
                    app.Purl = 'https://api.imjad.cn/pixiv/v2/?type=' + app.Ptype + '&id=' + app.Pid + '&page=' + app.Ppage;
                    break;
                case "search":
                    //若搜索关键词
                    if (app.PmodeSearch == 0) {
                        mdui.alert("必须指定mode!");
                        return false
                    }
                    app.Ptype = 'search';
                    app.Purl = 'https://api.imjad.cn/pixiv/v2/?type=search&mode=' + app.PmodeSearch + '&order=' + app.Porder + '&word=' + app.Pkey + '&page=' + app.Ppage;
                    break;
                case "rank":
                    //若查询排行榜
                    app.Ptype = 'rank';
                    app.Ppage = 1;
                    app.Pid = '';
                    date = '&date=' + app.Pdate;
                    app.Purl = 'https://api.imjad.cn/pixiv/v2/?type=rank&mode=' + app.PmodeRank + date;
                    break;
                case "illust":
                    //若查询插画
                    app.Ptype = 'illust';
                    app.Ppage = 1;
                    app.Purl = 'https://api.imjad.cn/pixiv/v2/?type=illust&id=' + app.Pid;
                    break;
                case "member":
                    //若查询画师
                    app.Ptype = 'member';
                    app.Ppage = 1;
                    app.Purl = 'https://api.imjad.cn/pixiv/v2/?type=member&id=' + app.Pid;
                    break;
                default:
                    //默认，也就是翻页
                    if (app.Ptype === 'search') {
                        app.Purl = 'https://api.imjad.cn/pixiv/v2/?type=search&mode=' + app.PmodeSearch + '&order=' + app.Porder + '&word=' + app.Pkey + '&page=' + app.Ppage;
                    } else {
                        app.Purl = 'https://api.imjad.cn/pixiv/v2/?type=' + app.Ptype + '&id=' + app.Pid + '&page=' + app.Ppage;
                    }
                }


                //若直接输入api
                if (typeof arguments[1] !== "undefined") {
                    app.Purl = arguments[1];
                    app.Ptype = parseUrl(app.Purl).type;
                    app.Pmode = parseUrl(app.Purl).mode;
                    app.Pid = parseUrl(app.Purl).id;
                }
                //保存记录到localStorage
                if (app.Purl === "") return false;
                try {
                    addHistory(app.Purl);
                    location.assign("#" + app.Purl);
                } catch (e) {
                    console.log("可能不支持localStorage");
                    //console.error(e)
                }
                //弹出loadingBox
                loadingBox.open();
                //获取数据
                axios.get(app.Purl).then(function (response) {
                        //关闭loadingBox
                        loadingBox.close();
                        location.hash = location.hash.replace('mdui-dialog', '');
                        //简单错误处理
                        if (JSON.stringify(response.data).length < 38) mdui.alert("请求可能失败，或者为空<br />出错json：<br />" + JSON.stringify(response.data));

                        if (response.data.hasOwnProperty("error")) {
                            app.Perror = response.data.error.message;
                            mdui.alert(app.Perror);
                            leftDrawer.open();
                        }
                        if (app.Ptype === "illust") {
                            let illust = arrayAddProxy(response.data.illust);
                            let content, url;
                            if (illust.hasOwnProperty("meta_single_page")) {
                                content = `<img src="${illust.meta_single_page.original_image_url}" alt="加载失败">`;
                                url = `<a href="${illust.meta_single_page.original_image_url}">${illust.meta_single_page.original_image_url}</a>`;
                            } else {
                                for (let o = 0; o < illust.meta_pages.length; o++) {
                                    content += `<img src="${illust.meta_pages[o].image_urls.original}"alt="加载失败">`;
                                    url += `<br /><a href="${illust.meta_pages[o].image_urls.original}">${illust.meta_pages[o].image_urls.original}</a>`;
                                }
                            }
                            let output = `<div class="mdui-typo"><h1>${illust.title}</h1>
<br />画师：${illust.user.name}｜${illust.user.id} <br />${content}
<br />说明：${illust.caption}
<br />时间：${illust.create_date}
<br />尺寸：${illust.height}×${illust.width}
<br />查看量：${illust.total_view}
<br />收藏数：${illust.total_bookmarks}
<br />图片url：${url}
</div>`;
                            mdui.alert(output);
                        } else if (app.Ptype === "member") {
                            let user = arrayAddProxy(response.data.user);
                            let output = `<ul class="mdui-list">
  <li class="mdui-list-item mdui-ripple">
    <div class="mdui-list-item-avatar"><img src="${user.profile_image_urls.medium}"/></div>
    <div class="mdui-list-item-content">${user.name}｜${user.account}｜${user.id}</div>
  </li>
</ul>`;
                            mdui.alert(output);
                        } else {
                            //清空原数据
                            app.PresponseA = '';
                            app.PresponseB = '';
                        }

                        //判断type，数组在开头
                        if (typeModeA.includes(app.Ptype)) {
                            //设置代理,模式A
                            app.PresponseA = arrayAddProxy(response.data.illusts);
                        }

                        if (typeModeB.includes(app.Ptype)) {
                            //设置代理,模式B
                            app.PresponseB = arrayAddProxy(response.data.user_previews);
                        }

                        //结束
                        leftDrawer.close();
                    })
                    //错误处理待定

                .catch(function (error) {
                    leftDrawer.open();
                    app.Perror = error;
                    mdui.alert(app.Perror);
                });
                //函数结束
            },
            getTags: function () {
                if (app.showHotTags) {
                    axios.get('https://api.imjad.cn/pixiv/v2/?type=tags').then(function (response) {
                        app.PresponseC = arrayAddProxy(response.data.trend_tags);
                    }).
                    catch(function (error) {
                        console.log(error);
                    }); //此方法尾部 
                } else {
                    app.PresponseC = '';
                }
            },
            clearResponse: function () {
                app.PresponseA = '';
                app.PresponseB = '';
                app.PresponseC = '';
                app.showHotTags = false;
                mdui.snackbar({
                    message: '成功！',
                    position: 'right-top'
                });
            }
    },
    watch: { //监控变量
        Ptype: function (nval, oval) {
                if (nval == 'search' || nval == 'following') {
                    app.isDisabled = false
                } else {
                    app.isDisabled = true
                }
            },
            showHotTags: function (nval, oval) {
                if (oval != nval) app.getTags();
            },
            picked: function (nval, oval) {
                if (oval != nval) localStorage.picked = app.picked;
            },
            Pquality: function (nval, oval) {
                if (oval != nval) localStorage.Pquality = app.Pquality;
            },
            isPreview: function (nval, oval) {
                if (oval != nval) localStorage.isPreview = app.isPreview;
            },
            showDetailTags: function (nval, oval) {
                if (oval != nval) localStorage.showDetailTags = app.showDetailTags;
            },
            showDetailMsg: function (nval, oval) {
                if (oval != nval) localStorage.showDetailMsg = app.showDetailMsg;
            }

    }

});
//vue配置结束

function addHistory(data) {
    app.localHistory.push(data);
    localStorage.history = JSON.stringify(app.localHistory);
}

function addStar(data) {
    app.localStar.push(data);
    localStorage.star = JSON.stringify(app.localStar);
    mdui.snackbar({
        message: '收藏成功！',
        position: 'right-top'
    });
}

function recovery() {
    app.clearResponse();
    app.isPreview = app.showHotTags = app.showDetailTags = app.showDetailMsg = false;
    app.Ppage = 1;
    app.Purl = '';
    localStorage.clear();
    mdui.snackbar({
        message: '清除成功！',
        position: 'right-top'
    });
}

function parseUrl(url) {
    var obj = {};
    var start = url.indexOf("?") + 1;
    var str = url.substr(start);
    var arr = str.split("&");
    for (var i = 0; i < arr.length; i++) {
        var arr2 = arr[i].split("=");
        obj[arr2[0]] = arr2[1];
    }
    return obj;
}

function Bool(val) {
    if (val === "false" || val === "0" || val === 0) return false;
    if (val === "true" || val === "1" || val === 1) return true
}

function showHelp() {
    mdui.dialog({
        title: '使用说明',
        content: `<p>
  本界面通过调用AD's API获取数据
 ，通过代理获取图片<br />
主要使用Vue和MDUI框架</p>
<p>打开侧边栏<i class="mdui-icon material-icons">menu</i>进行查询，查询完毕后，侧边栏自动关闭<br />
底部按钮<i class="mdui-icon material-icons">wallpaper</i><i class="mdui-icon material-icons">visibility</i><i class="mdui-icon material-icons">star</i><i class="mdui-icon material-icons">tune</i>可进行设置<br />若图片无法加载，请修改<i class="mdui-icon material-icons">tune</i><strong>并刷新</strong>
</p>`,
        buttons: [{
            text: '了解',
            onClick: function (inst) {
                location.hash = ''
            }
        }]
    });
    localStorage.isFirst = "no"
}
app.mdui = mdui;
try { //恢复上次设置
    if (typeof localStorage.isFirst === "undefined") {
        localStorage.Pquality = "medium";
        showHelp();
    } else {
        app.picked = localStorage.picked;
        app.Pquality = localStorage.Pquality;
        app.isPreview = Bool(localStorage.isPreview);
        app.showDetailTags = Bool(localStorage.showDetailTags);
        app.showDetailMsg = Bool(localStorage.showDetailMsg);
        app.localHistory = JSON.parse(localStorage.history);
        if (localStorage.star) app.localStar = JSON.parse(localStorage.star);
    }
} catch (e) { //可以本地运行
    console.log("可能不支持localStorage");
    console.error(e)
}

window.onload = function () {
    //实例化抽屉
    window.leftDrawer = new mdui.Drawer('#left-drawer');
    window.loadingBox = new mdui.Dialog('#loading-box');
    leftDrawer.open();
    if (location.href.lastIndexOf("#") > -1 && !location.href.includes("#mdui-dialog") && location.hash.length > 7) {
        leftDrawer.close();
        //刷新时从#后重载
        app.parseApi(null, location.hash.slice(1));
    }
}

function arrayAddProxy(arr) { //给对象添加代理
    switch (app.picked) {
    case 'planA':
        var url = JSON.stringify(arr).replace(/https:\/\/i.pximg/g, "http://pixiv.dns.navy/view.php/https://i.pximg");
        break;

    case 'planB':
        var url = JSON.stringify(arr).replace(/https:\/\/i.pximg.net/g, "https://www.moe123.net/api/imageproxy");
        break;

    case 'planC':
        var url = JSON.stringify(arr).replace(/https:\/\/i.pximg/g, "http://i.kka.pub/PixivViewer/view.php/https://i.pximg");
        break;

    case 'planD':
        var url = JSON.stringify(arr).replace(/i.pximg.net/g, "i.pixiv.cat");
        break;


    default:
        var url = JSON.stringify(arr).replace(/i.pximg.net/g, "www.moe123.net/api/imageproxy");
    }
    url = JSON.parse(url);
    return url;
}