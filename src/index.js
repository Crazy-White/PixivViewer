var mode = ['day', 'week', 'month', 'week_rookie', 'week_original', 'day_male', 'day_female', 'day_r18', 'weekly_r18', 'day_male_r18', 'day_female_r18', 'week_r18', 'week_r18g'];
function get(){
var id = document.getElementsByName('id')[0].value;
document.getElementsByName("view")[0].disabled = true;
document.getElementById('loading').style.display = "";
document.getElementById('pixiv_url').style.display = "none";
document.getElementsByClassName("meta")[0].innerText = "读取中…";
if(mode.indexOf(id) != -1){
yesterday = getYesterdayFormatDate();
data = 'type=rank&mode=' + id + '&date=' + yesterday ;
console.log("请求：https://api.imjad.cn/pixiv/v2/\n" + "参数：type=rank mode=" + id + " date=" + yesterday);
}
else{
data = 'type=illust&id=' + id;
console.log("请求：https://api.imjad.cn/pixiv/v2/\n" + "参数：type=illust id=" + id);
}
document.getElementsByName("code")[0].value = 'https://api.imjad.cn/pixiv/v2/?' + data;
Ajax( //Ajax(type, url, data, success, failed)
'get', 
'https://api.imjad.cn/pixiv/v2/', 
data, 
function(data){
data = JSON.parse(data);
if(!data.error){
if(mode.indexOf(id) != -1){
var rand = Math.floor(Math.random() * (data.response[0].works.length));
console.log('rank:' + (rand + 1));
var url = data.response[0].illusts[rand].image_urls.medium.replace("pximg.net","pixiv.cat");
var create_date = new Date(data.response[0].illusts[rand].create_date);
document.getElementsByClassName("meta")[0].innerText = "ID：" + data.response[0].illusts[rand].id + " 作品：" + data.response[0].illusts[rand].title +" 投稿：" + data.response[0].illusts[rand].user.name + " @ "+ create_date.toLocaleString();
document.getElementById("pixiv_url").href = "painting.php?id=" + data.response[0].works[rand].id;
document.getElementById("pixiv_img").title = data.response[0].works[rand].caption;

}else{

var create_date = new Date(data.illust.create_date);
document.getElementsByClassName("meta")[0].innerText = "ID：" + data.illust.id + " 作品：" + data.illust.title +" 投稿：" + data.illust.user.name + " @ "+ create_date.toLocaleString();
var url = data.illust.image_urls.medium.replace("pximg.net","pixiv.cat");
document.getElementById("pixiv_url").href = "painting.php?id=" + data.illust.id;
document.getElementById("pixiv_img").title = data.illust.caption;
}
if(getURLParam('r18') != 1){
if(data.illust.tags[0].name == 'R-18' || data.illust.tags[0].name == 'R-18G') url = 'https://i.loli.net/2019/11/02/bFq4MGQTEUIVx2t.png';
//url替换方法 url.replace("pximg.net","pixiv.cat");
}
document.getElementById("pixiv_img").src = url;
document.getElementById('loading').style.display = "none";
document.getElementById('pixiv_url').style.display = "inline-block";
}else {
document.getElementsByClassName("meta")[0].innerText = "发生错误：" + data.error.user_message;
document.getElementById("pixiv_url").href = "javascript:void(0);";
document.getElementById('loading').style.display = "none";
document.getElementById('pixiv_url').style.display = "inline-block";
document.getElementById("pixiv_img").src = "https://img.imjad.cn/images/2016/12/23/404.jpg";
document.getElementById("pixiv_img").title = "发生错误：" + data.error.user_message;
}
document.getElementsByName("view")[0].disabled = false;
}, 
function(error){
document.getElementsByClassName("meta")[0].innerText = "与api服务器连接出错";
document.getElementById("pixiv_url").href = "javascript:void(0);";
document.getElementById('loading').style.display = "none";
document.getElementById('pixiv_url').style.display = "inline-block";
document.getElementById("pixiv_img").src = "https://img.imjad.cn/images/2016/12/23/404.jpg";
document.getElementById("pixiv_img").title = "与api服务器连接出错";
document.getElementsByName("view")[0].disabled = false;
});
}
function getYesterdayFormatDate() {
var date = new Date();
date.setTime(date.setHours(date.getHours() - 48))
var seperator1 = "-";
var month = date.getMonth() + 1;
var strDate = date.getDate();
if (month >= 1 && month <= 9) {
month = "0" + month;
}
if (strDate >= 0 && strDate <= 9) {
strDate = "0" + strDate;
}
var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate;
return currentdate;
}
function search(word, page){
Ajax( //Ajax(type, url, data, success, failed)
'get', 
'https://api.imjad.cn/pixiv/v2/', 
'type=search&word=' + word + '&page=' + page, 
function(data){
data = JSON.parse(data);
var arr = data.illusts.sort(sortSearchResult);
var id,list;
var page = document.getElementsByName('page')[0].value;
document.getElementById('search_result').innerHTML = '';
console.log(page);
for(var i=arr.length-1;i>=0;--i){
var id = arr[i].id;
var list = document.createElement('li');
list.id = id;
list.onclick = function(){
document.getElementsByName('id')[0].value = this.id;
for(var i =0;i<search_result.length;i++){
search_result[i].classList.remove('focus');
}
this.classList.add('focus');
get();
}

var views = arr[i].total_view;
var count = arr[i].total_bookmarks;
var ratio = (arr[i].total_bookmarks / arr[i].total_view).toFixed(3);

list.innerText = 'ID:' + paddingRight(id, 12) + 'Views:' + paddingRight(views, 9) + 'Bookmarks:' + paddingRight(count, 8) + 'Ratio:' + ratio;
if(views >= 100){
if(ratio >= 0.2){
list.classList.add('ratio-fantastic');
}else if(ratio >= 0.15){
list.classList.add('ratio-great');
}else if(ratio >= 0.1){
list.classList.add('ratio-good');
}else if(ratio >= 0.05){
list.classList.add('ratio-normal');
}else if(ratio >= 0.03){
list.classList.add('ratio-notbad');
}else{
list.classList.add('ratio-ordinary');
}
}else{
if(ratio >= 0.05){
list.classList.add('ratio-notbad');
}else{
list.classList.add('ratio-ordinary');
}
}
document.getElementById('search_result').appendChild(list);
}

search_result = document.getElementById('search_result').getElementsByTagName('li');
for(var i =0;i<search_result.length;i++){
if(search_result[i].id == document.getElementsByName('id')[0].value){
search_result[i].classList.add('focus');
}
}

document.getElementById('prev_page').onclick = function(){
if(page > 1){
window.page--;
document.getElementsByName('page')[0].value = page;
search(window.keyword, window.page);
}
}
document.getElementById('next_page').onclick = function(){
window.page++;
document.getElementsByName('page')[0].value = window.page;
search(window.keyword, window.page);
}
document.getElementById('search_result').appendChild(list);
}, 
function(error){
var spans = document.getElementsByClassName("update")[0].getElementsByTagName("span");
for (var i=0;i<spans.length;i++){
spans[i].innerHTML = "读取失败";
}
});
}
function sortSearchResult(a,b){
var count1 = a.total_bookmarks;
var count2 = b.total_bookmarks;
var views1 = a.total_view;
var views2 = b.total_view;
var ratio1 = count1 / views1;
var ratio2 = count2 / views2;
//收藏率为 0 时按浏览数排序
//收藏率为 1:0 时a在前
//浏览数大于等于 100 时按收藏率排序
//浏览数小于 100 且收藏数相等时按收藏率排序
//浏览数小于 100 时按收藏数排序
if(ratio1 == 0 && ratio2 == 0){
return views1 - views2;
}else if(isFinite(ratio1) && isFinite(ratio2)){
if(views1 >= 100 && views2 >= 100){
return ratio1 - ratio2;
}else if(count1 == count2){
return ratio1 - ratio2;
}else{
return count1 - count2;
}
}else{
return -1;
}
}
function paddingRight(str,lenght){
if(str.length >= lenght)
return str;
else
return paddingRight(str + " ",lenght);
}
document.getElementsByName('search')[0].onclick = function(){
window.keyword = document.getElementsByName('keyword')[0].value;
window.page = document.getElementsByName('page')[0].value; 
search(window.keyword, window.page);
};
</script> 
     <script>
function Ajax(type, url, data, success, failed){
// 创建ajax对象
var xhr = null;
if(window.XMLHttpRequest){
xhr = new XMLHttpRequest();
} else {
xhr = new ActiveXObject('Microsoft.XMLHTTP')
}

var type = type.toUpperCase();
// 用于清除缓存
var random = Math.random();

if(typeof data == 'object'){
var str = '';
for(var key in data){
str += key+'='+data[key]+'&';
}
data = str.replace(/&$/, '');
}

if(type == 'GET'){
if(data){
xhr.open('GET', url + '?' + data, true);
} else {
xhr.open('GET', url + '?t=' + random, true);
}
xhr.send();

} else if(type == 'POST'){
xhr.open('POST', url, true);
// 如果需要像 html 表单那样 POST 数据，请使用 setRequestHeader() 来添加 http 头。
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.send(data);
}

// 处理返回数据
xhr.onreadystatechange = function(){
if(xhr.readyState == 4){
if(xhr.status == 200){
success(xhr.responseText);
} else {
if(failed){
failed(xhr.status);
}
}
}
}
}

Ajax( //Ajax(type, url, data, success, failed)
'get', 
'https://api.imjad.cn/pixiv/counter.php', 
'', 
function(data){
data = JSON.parse(data);
document.getElementById("index").innerHTML = data.index;
document.getElementById("api").innerHTML = data.api;
document.getElementById("hit").innerHTML = data.hit;
document.getElementById("speed").innerHTML = data.speed;
}, 
function(error){
var spans = document.getElementsByClassName("update")[0].getElementsByTagName("span");
for (var i=0;i<spans.length;i++){
spans[i].innerHTML = "读取失败";
}
});
function getURLParam(e) {
var t = new RegExp("(^|&)" + e + "=([^&]*)(&|$)","i")
, r = window.location.search.substr(1).match(t);
return r ? unescape(r[2]) : null
}

(function(){
if(getURLParam('id') != null){
document.getElementsByName('id')[0].value = getURLParam('id');
get();
}
if(getURLParam('s') != null){
var page = getURLParam('page') || 1;
document.getElementsByName('page')[0].value = page;
document.getElementsByName('keyword')[0].value = getURLParam('s');
search(getURLParam('s'), page);
}
function getViewDefaultIDs(){
var arr = [];
Ajax( //Ajax(type, url, data, success, failed)
'get', 
'https://api.imjad.cn/pixiv/v2/', 
'type=favorite&id=16361124', 
function(data){
data = JSON.parse(data);
var item;
item = data.illusts;
for(var i=0;i<item.length;i++){
arr.push(item[i].id);
}
}, 
function(error){
arr = [51182825, 51321153, 56614444, 58497663];
}
);
return arr
};
function getSearchDefaultWords(){
var arr = [];
Ajax( //Ajax(type, url, data, success, failed)
'get', 
'https://api.imjad.cn/pixiv/v2/', 
'type=tags', 
function(data){
data = JSON.parse(data);
for(var i=0;i<data.trend_tags.length;i++){
arr.push(data.trend_tags[i].tag);
}
}, 
function(error){
arr = ['香風智乃', 'ご注文はうさぎですか?', 'Miku', 'この素晴らしい世界に祝福を!', 'エロマンガ先生', 'Charlotte', 'ゼロから始める魔法の書', 'チルノ', 'ロリ', '中二病でも恋がしたい', '博麗霊夢', '四月は君の嘘', '女の子', 'Love Live!', '尻神様', '東方', '春日野穹', '極上の貧乳', '氷菓', 'CLANNAD', '第六駆逐隊', '雪ノ下雪乃', '風景', '艦これ', '水着', '狼と香辛料', '島風', 'Angel Beats!', '冴えない彼女の育てかた', '澤村・スペンサー・英梨々', 'やはり俺の青春ラブコメはまちがっている。', '千反田える', '忍野忍'];
}
);
return arr
};

function setSearchDefaultWords(){
document.getElementsByName('keyword')[0].value = words[Math.floor(Math.random()*words.length)];
};

function setViewDefaultIDs(){
document.getElementsByName('id')[0].value = ids[Math.floor(Math.random()*ids.length)];
};

words = getSearchDefaultWords();
ids = getViewDefaultIDs();

window.setInterval(setSearchDefaultWords, 30000);
window.setInterval(setViewDefaultIDs, 30000);
})();