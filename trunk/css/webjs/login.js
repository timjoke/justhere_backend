//从localstorage中读取user
function init_user()
{
    document.title = user.userName;
    document.getElementById("userheadimg").src = user.userHead;
    document.getElementById("image_url_img").src = user.userHead;
    document.getElementById("username").innerText = user.userName;
    document.getElementById("userid").innerText = "yaohe" + pad(user.userID, 6);
    $('.access_token').each(function() {
        $(this).attr("value", user.userID);
    });
    init_friend();
    init_amap();
    getUnreadCount();
    var myDate = new Date();
    var beginDate = new Date(Date.parse(myDate) - (864000000 * 1));
    document.getElementById("begin_time").value = beginDate.format("yyyy-MM-dd");
    init_yaohe(position);
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
};

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

function search() {
    if (mapObj)
    {
        var pp = mapObj.getCenter();
    }
    else
    {
        pp = position;
    }
    init_yaohe(pp);
}

