//从localstorage中读取user
function init_user()
{
    //service_url = "http://api.iyaohe.cc/";
    document.title = user.userName;
    document.getElementById("userheadimg").src = user.userHead;
    document.getElementById("image_url_img").src = user.userHead;
    document.getElementById("username").innerText = user.userName;
    setTime();
    getTime();
    document.getElementById("userid").innerText = "yaohe" + pad(user.userID, 6);
    $('.access_token').each(function() {
        $(this).attr("value", user.userID);
    });
    //init_friend();
    init_amap_location();
    //init_yaohe(position);
    //getUnreadCount();
    cleanMarker();
    //locationtest();
    //lastid = 0;
    lastlocation = null;
    AMap.event.addListener(mapObj, "complete", completeEventHandler);
}
var marker = null;
var lastid = 800;
var lastlocation = null;
var colorflag = true;
var viewflag = true;
var lineArr = new Array();
var begin_time = "";
var end_time = "";

function changeview()
{
    clearInterval(getUnreadTimer);
    cleanMarker();
    lastid = 800;
    lastlocation = null;
    viewflag = !viewflag;
    //小汽车页面
    if (viewflag)
    {
        isfirst = false;
        updateLocationByCar();
    }
    else
    {
        updateLocationByMark();
    }

}

function search()
{
    clearInterval(getUnreadTimer);
    cleanMarker();
    lastid = 800;
    lastlocation = null;
    updateLocationByMark();
}

function completeEventHandler()
{
    start();
}
function start()
{
    if (viewflag)
        updateLocationByCar();
    else
        updateLocationByMark();
}
function startAnimation() {
    clearInterval(getUnreadTimer);
    cleanMarker();
    lastid = 800;
    lastlocation = null;
    beginAnimation();
}
function stopAnimation() {
    marker.stopMove();
    viewflag = false;
    changeview();
}

var isfirst = false;
function updateLocationByCar() {
    document.getElementById("searchDate").style.display = "none";
    getTime();
    var params = '{';
    params += '"access_token":' + user.userID + ',';
    params += '"b_time":"' + begin_time + '",';
    //params += '"e_time":"' + end_time + '",';
    params += '"lastid":' + lastid;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/locationGet", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    if (isfirst)
                    {
                        //在地图上绘制折线		
                        for (var i = 0; i < result.data.length; i++)
                        {
                            lastid = result.data[i].locID;

                            var p = new AMap.LngLat(result.data[i].longitude, result.data[i].latitude);
                            var marker = new AMap.Marker({
                                map: mapObj,
                                position: p,
                                icon: "http://api.iyaohe.cc/images/" + (markers.length == 0 ? 1 : markers.length) % 51 + ".png",
                                offset: new AMap.Pixel(-10, -34),
                                data: result.data[i]
                            });
                            markers.push(marker);
                            var itemDIV = document.createElement("div");
                            itemDIV.id = "id_yaohe_" + result.data[i].locID;
                            itemDIV.innerHTML = init_locationTemplate(result.data[i], true).join("");
                            if (leftDiv.childElementCount > 0)
                            {
                                leftDiv.insertBefore(itemDIV, leftDiv.childNodes[0]);
                            }
                            else
                            {
                                leftDiv.appendChild(itemDIV);
                            }

                            lastlocation = result.data[i];
                        }
                    }
                    else if (result.data.length > 0 && !isfirst)
                    {
                        isfirst = true;
                        var data_length = result.data.length;
                        var p = new AMap.LngLat(result.data[data_length - 1].longitude, result.data[data_length - 1].latitude);
                        var marker = new AMap.Marker({
                            map: mapObj,
                            position: p,
                            icon: "http://api.iyaohe.cc/images/" + (markers.length == 0 ? 1 : markers.length) % 51 + ".png",
                            offset: new AMap.Pixel(-10, -34),
                            data: result.data[data_length - 1]
                        });
                        markers.push(marker);
                        var itemDIV = document.createElement("div");
                        itemDIV.id = "id_yaohe_" + result.data[data_length - 1].locID;
                        itemDIV.innerHTML = init_locationTemplate(result.data[data_length - 1], true).join("");
                        if (leftDiv.childElementCount > 0)
                        {
                            leftDiv.insertBefore(itemDIV, leftDiv.childNodes[0]);
                        }
                        else
                        {
                            leftDiv.appendChild(itemDIV);
                        }

                        lastlocation = result.data[data_length - 1];
                        lastid = result.data[data_length - 1].locID;

//                        var data_length = result.data.length;
//                        var p = new AMap.LngLat(result.data[data_length - 1].longitude, result.data[data_length - 1].latitude);
////                        if (marker != null)
////                        {
////                            marker.setMap(null);
////                        }
//                        var marker1 = new AMap.Marker({
//                            map: mapObj,
//                            //draggable:true, //是否可拖动
//                            position: p, //基点位置
//                            //icon: "http://code.mapabc.com/images/car_03.png", //marker图标，直接传递地址url
//                            icon: "http://api.iyaohe.cc/images/" + (markers.length == 0 ? 1 : markers.length) % 51 + ".png",
//                            offset: new AMap.Pixel(-26, -13), //相对于基点的位置
//                            autoRotation: true,
//                        });
//                        mapObj.setCenter(p);
                    }
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
                getUnreadTimer = window.setTimeout(function() {
                    updateLocationByCar();
                }, 3000);
            });
}

function updateLocationByMark() {
    document.getElementById("searchDate").style.display = "block";
    getTime();
    var params = '{';
    params += '"access_token":' + user.userID + ',';
    params += '"b_time":"' + begin_time + '",';
    params += '"e_time":"' + end_time + '",';
    params += '"lastid":' + lastid;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/locationGet", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    for (var i = 0; i < result.data.length; i++)
                    {
                        lastid = result.data[i].locID;
                        var p = new AMap.LngLat(result.data[i].longitude, result.data[i].latitude);
                        var marker = new AMap.Marker({
                            map: mapObj,
                            position: p,
                            icon: "http://api.iyaohe.cc/images/" + (markers.length == 0 ? 1 : markers.length) % 51 + ".png",
                            offset: new AMap.Pixel(-10, -34),
                            data: result.data[i]
                        });
                        markers.push(marker);

                        var itemDIV = document.createElement("div");
                        itemDIV.id = "id_yaohe_" + result.data[i].locID;
                        itemDIV.innerHTML = init_locationTemplate(result.data[i], true).join("");
                        if (leftDiv.childElementCount > 0)
                        {
                            leftDiv.insertBefore(itemDIV, leftDiv.childNodes[0]);
                        }
                        else
                        {
                            leftDiv.appendChild(itemDIV);
                        }

                        lastlocation = result.data[i];
                    }
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
//                getUnreadTimer = window.setTimeout(function() {
//                    start();
//                }, 3000);
            });
}

function beginAnimation() {
    document.getElementById("searchDate").style.display = "block";
    getTime();
    var params = '{';
    params += '"access_token":' + user.userID + ',';
    params += '"b_time":"' + begin_time + '",';
    params += '"e_time":"' + end_time + '",';
    params += '"lastid":' + 800;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/locationGet", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    lineArr = new Array();
                    leftDiv.innerHTML = "";
                    //在地图上绘制折线		
                    for (var i = 0; i < result.data.length; i++)
                    {
                        lastid = result.data[i].locID;
                        var p = new AMap.LngLat(result.data[i].longitude, result.data[i].latitude);
                        lineArr.push(p);

                        var itemDIV = document.createElement("div");
                        itemDIV.id = "id_yaohe_" + result.data[i].locID;
                        itemDIV.innerHTML = init_locationTemplate(result.data[i], true).join("");
                        if (leftDiv.childElementCount > 0)
                        {
                            leftDiv.insertBefore(itemDIV, leftDiv.childNodes[0]);
                        }
                        else
                        {
                            leftDiv.appendChild(itemDIV);
                        }

                        lastlocation = result.data[i];
                    }
                    var polyline = new AMap.Polyline({
                        map: mapObj,
                        path: lineArr,
                        strokeColor: "#FF33FF", //线颜色
                        strokeOpacity: 1, //线透明度
                        strokeWeight: 3, //线宽
                        strokeStyle: "solid"//线样式
                    });
                    mapObj.setFitView();
                    marker = new AMap.Marker({
                        map: mapObj,
                        //draggable:true, //是否可拖动
                        position: p, //基点位置
                        icon: "http://code.mapabc.com/images/car_03.png", //marker图标，直接传递地址url
                        offset: new AMap.Pixel(-26, -13), //相对于基点的位置
                        autoRotation: true
                    });
                    var speed = document.getElementById("back_speed").value;
                    marker.moveAlong(lineArr, speed);
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

function init_locationTemplate(locat, hasADiv)
{
    var yaoheInfoTemplate = [];
    if (hasADiv)
    {
        //是否可以导航到地图上的信息
        yaoheInfoTemplate.push("<a href='javascript:leftLocation_click({0});'>".format(locat.locID));
    }
    if (lastlocation != null && lastlocation.createtime1 != locat.createtime1)
    {
        colorflag = !colorflag;
    }
    yaoheInfoTemplate.push("<div class='yaoheinfowell well' style='background-color:{0};'>".format(colorflag ? 'yellow' : 'aquamarine'));
    yaoheInfoTemplate.push("<div class='content_row'>");
    yaoheInfoTemplate.push("<span class='username'>id:{0}</span>".format(locat.locID));
    yaoheInfoTemplate.push("</div>");
    yaoheInfoTemplate.push("<div class='content_row'>");
    yaoheInfoTemplate.push("<span>经度:{0}</span><br>".format(locat.longitude));
    yaoheInfoTemplate.push("<span>纬度:{0}</span><br>".format(locat.latitude));
    yaoheInfoTemplate.push("<span>客户端时间:{0}</span><br>".format(locat.createtime1));
    yaoheInfoTemplate.push("<span>服务器时间:{0}</span>".format(locat.createtime));
    yaoheInfoTemplate.push("</div>");
    yaoheInfoTemplate.push("</div>");
    if (hasADiv)
    {
        yaoheInfoTemplate.push("</a>");
    }
    return yaoheInfoTemplate;
}

function leftLocation_click(locid)
{
    for (var i = 0; i < markers.length; i++)
    {
        if (markers[i].bd.data.locID == locid)
        {
            mapObj.setCenter(markers[i].getPosition());
            //mapObj.setZoom(14);
            infoWindow.setContent(createInfoWindow(init_locationTemplate(markers[i].bd.data, false).join("")));
            infoWindow.open(mapObj, markers[i].getPosition());
            //markers[i].setAnimation('AMAP_ANIMATION_BOUNCE');
            break;
        }
    }
}

//清楚地图上所有标记和右边吆喝栏位
function cleanMarker()
{
    if (currentMarker != null)
    {
        currentMarker = null;
    }
    if (mapObj != null)
    {
        lineArr = [];
        markers = [];
        mapObj.clearMap();
    }
    leftDiv.innerHTML = "";
    if (infoWindow != null)
    {
        infoWindow.close();
    }
}

//弹出登陆modal
function showLoginModal() {
    $('#loginModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
    $('#loginModal').modal('show');
}

//弹出编辑用户信息modal
function showFinishUserModal()
{
    document.getElementById("usernameModal").innerText = user.userName;
    document.getElementById("telphone").value = user.telphone;
    document.getElementById("email").value = user.email;
    document.getElementById("passwordModal").value = user.password;
    document.getElementById("nickName").value = user.nickName;
    if (user.sex == 1)
    {
        document.getElementById("male").checked = true;
    }
    else
    {
        document.getElementById("female").checked = true;
    }
    document.getElementById("image_url").value = user.userHead;
    $('#finishUserModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
    $('#finishUserModal').modal('show');
}

function saveUserInfo() {
    $("#f_finishUser").ajaxSubmit({
        type: 'post',
        url: 'app/finishUserInfo',
        success: function(result) {
            result = getJSONObj(result);
            if (result == null)
            {
                return;
            }
            if (result.code == 200)
            {
                user.password = result.data.password;
                user.telphone = result.data.telphone;
                user.email = result.data.email;
                user.sex = result.data.sex;
                user.userHead = result.data.userHead;
                user.nickName = result.data.nickName;
                document.getElementById("userheadimg").src = user.userHead;
                window.localStorage.setItem("user", JSON.stringify(user));
                $('#finishUserModal').modal('hide');
            }
            else
            {
                alert("code:" + result.code + ",message:" + result.message);
            }
        },
        error: function(XmlHttpRequest, textStatus, errorThrown) {
            alert(XmlHttpRequest + "," + textStatus + "," + errorThrown);
        }
    });
}

//登陆click事件
function login()
{
    var username_txt = document.getElementById("username_txt").value;
    var pwd_txt = document.getElementById("pwd_txt").value;
    if (username_txt == "")
    {
        alert("请输入用户名！");
        return;
    }
    if (pwd_txt == "")
    {
        alert("请输入密码！");
        return;
    }
    var json_str = "{";
    json_str += '"name":"' + username_txt + '",';
    //json_str += '"device_token":"20246d01665988639d336f8c02c935c2e0accf63a97c05ac8025db39abf942c9",';
    json_str += '"pwd":"' + pwd_txt + '"';
    json_str += '}';
    var json = JSON.parse(json_str);
    $.post(service_url + "app/login", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    user = result.data.user;
                    window.localStorage.setItem("user", JSON.stringify(user));
                    init_user();
                    $('#loginModal').modal('hide');
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

function register()
{
    var username_txt = document.getElementById("username_txt").value;
    var pwd_txt = document.getElementById("pwd_txt").value;
    if (username_txt == "")
    {
        alert("请输入用户名！");
        return;
    }
    if (pwd_txt == "")
    {
        alert("请输入密码！");
        return;
    }
    var json_str = "{";
    json_str += '"username":"' + username_txt + '",';
    json_str += '"pwd":"' + pwd_txt + '"';
    json_str += '}';
    var json = JSON.parse(json_str);
    $.post(service_url + "app/register", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    alert("注册成功！");
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

//登陆click事件
function loginOut()
{
    var curr_userid = user.userID;
    user = null;
    window.localStorage.removeItem("user");
    document.getElementById("username_txt").value = "";
    document.getElementById("pwd_txt").value = "";
    if (getUnreadTimer != null)
    {
        window.clearTimeout(getUnreadTimer);
        getUnreadTimer = null;
    }
    cleanMarker();
    init();

    var json = JSON.parse('{"access_token":' + curr_userid + ',"os_type":"web"}');
    $.post(service_url + "app/loginOut", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {

                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

//初始化高德地图
function init_amap_location()
{
    if (infoWindow)
    {
        infoWindow.close();
        infoWindow = null;
    }
    position = new AMap.LngLat(116.397428, 39.90923);//天安门坐标
    //position = new AMap.LngLat(116.61123, 39.92385);
    mapObj = new AMap.Map("mapcontainer", {
        view: new AMap.View2D({
            center: position,
            zoom: 11,
            rotation: 0
        }),
        lang: "zh_cn"//设置语言类型，中文简体
    });


    mapObj.plugin(["AMap.ToolBar", "AMap.OverView", "AMap.Scale"], function() {
        //加载工具条
        tool = new AMap.ToolBar({
            direction: false, //隐藏方向导航
            ruler: false, //隐藏视野级别控制尺
            autoPosition: false//禁止自动定位
        });
        mapObj.addControl(tool);
        //加载鹰眼
        view = new AMap.OverView();
        mapObj.addControl(view);
        //加载比例尺
        scale = new AMap.Scale();
        mapObj.addControl(scale);
    });

    //设置右边div的高度和地图一样高
    $("#leftPage").height($("#mapcontainer").height());
    infoWindow = new AMap.InfoWindow({
        autoMove: true, //是否自动调整信息窗体至视野内
        isCustom: true, //使用自定义窗体
        offset: new AMap.Pixel(16, -45),
        content: ""
    });
}


function getTime()
{
    begin_time = document.getElementById("begin_time").value;
    end_time = document.getElementById("end_time").value;
}

function setTime()
{
    var myDate = new Date();
    var beginDate = new Date(Date.parse(myDate) - (86400000 * 1));
    document.getElementById("begin_time").value = beginDate.format("yyyy-MM-dd hh:mm:ss");
    document.getElementById("end_time").value = myDate.format("yyyy-MM-dd hh:mm:ss");
}

Date.prototype.format = function(format) {
    var o = {
        "M+": this.getMonth() + 1, //month 
        "d+": this.getDate(), //day 
        "h+": this.getHours(), //hour 
        "m+": this.getMinutes(), //minute 
        "s+": this.getSeconds(), //second 
        "q+": Math.floor((this.getMonth() + 3) / 3), //quarter 
        "S": this.getMilliseconds() //millisecond 
    }

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }

    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}
