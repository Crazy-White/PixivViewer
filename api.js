const http = require('http');
const PixivApi = require('pixiv-api-client');
const pixiv = new PixivApi();
const username = 'p站账号';
const password = 'p站密码';
const head = pixiv.login(username, password).then(function () {
    return this;
});
//解析url
function parseUrl(url) {
    var obj = {};
    var start = url.indexOf('?') + 1;
    var str = url.substr(start);
    var arr = str.split('&');
    for (var i = 0; i < arr.length; i++) {
        var arr2 = arr[i].split('=');
        obj[arr2[0]] = arr2[1];
    }
    return obj;
}
function isNum(val) {
    if (isNaN(Number(val))) {
        return false;
    } else {
        return true;
    }
}

const server = http.createServer(function (request, response) {
    if (request.url == '/favicon.ico') {
        response.end('nothing~');
    }

    console.log(request.method + ': ' + request.url);

    const $_GET = parseUrl(request.url);

    response.setHeader('Access-Control-Allow-Origin', '*');
    response.setHeader('Access-Control-Allow-Methods', 'PUT,POST,GET,DELETE,OPTIONS');
    response.setHeader('Access-Control-Allow-Headers', 'X-Requested-With');
    response.writeHead(200, { 'Content-Type': 'application/json' });

    //start
    switch ($_GET['type']) {
        case 'rank':
            head.then(() => {
                return pixiv.illustRanking($_GET['date'], $_GET['mode']).then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        case 'search':
            head.then(() => {
                return pixiv.searchIllust($_GET['word'], $_GET['mode']).then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        case 'illust':
            head.then(() => {
                return pixiv.illustDetail($_GET['id']).then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        case 'member_illust':
            head.then(() => {
                return pixiv.userIllusts($_GET['id']).then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        case 'member':
            head.then(() => {
                return pixiv.userDetail($_GET['id']).then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        case 'related':
            head.then(() => {
                return pixiv.illustRelated($_GET['id']).then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        case 'favorite':
            head.then(() => {
                return pixiv.userBookmarksIllust($_GET['id'], $_GET['option']).then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        case 'following':
            if (Number($_GET['page']) > 1 && isNum($_GET['page'])) {
                head.then(() => {
                    return pixiv
                        .userFollowing($_GET['id'], $_GET['option'])
                        .then(json => {
                            let goTo = json.next_url.replace('&offset=30', '&offset=' + Number($_GET['page']) * 30);
                            console.log(goTo);
                            return pixiv.requestUrl(goTo);
                        })
                        .then(json => {
                            response.end(JSON.stringify(json));
                        });
                });
            } else {
                head.then(() => {
                    return pixiv.userFollowing($_GET['id'], $_GET['option']).then(json => {
                        response.end(JSON.stringify(json));
                    });
                });
            }
            break;

        case 'follower':
            head.then(() => {
                return pixiv.userFollowing($_GET['id'], $_GET['option']).then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        case 'recommended':
            head.then(() => {
                return pixiv.userRecommended().then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        case 'tags':
            head.then(() => {
                return pixiv.trendingTagsIllust().then(json => {
                    response.end(JSON.stringify(json));
                });
            });
            break;

        default:
            response.end(JSON.stringify({ error: 'no input' }));
    } //end
});

server.listen(8080);

console.log('Server is running at http://127.0.0.1:8080/');
