//初始化我的好友
function init_friend()
{
    var json = JSON.parse('{"access_token":' + user.userID + '}');
    $.post(service_url + "app/getAllFriends", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    user.friends = result.data;
                    init_myfriend_div(result.data);
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

function init_myfriend_div(data)
{
    var friendsDiv = document.getElementById("friendsDiv");
    friendsDiv.innerHTML = "";
    for (var i = 0; i < data.length; i++)
    {
        var itemDIV = document.createElement("div");
        itemDIV.className = "friendsDiv";
        var friendStatus = data[i].status == null ? "0" : data[i].status;
        itemDIV.innerHTML = getFriendTemplate(friendStatus, data[i]);
        friendsDiv.appendChild(itemDIV);
    }
}

//弹出我的好友modal
function showFriendsModal()
{
    var params = '{';
    params += '"access_token":' + user.userID;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/getAllFriends", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    $('#friendsModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
                    $('#friendsModal').modal('show');
                    init_myfriend_div(result.data);
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

//查找朋友
function findFriend() {
    var usernamesearch = document.getElementById("searchtxt").value;
    if (usernamesearch == "")
    {
        init_myfriend_div(user.friends);
        return;
    }
    var params1 = '{';
    params1 += '"username":"' + usernamesearch + '",';
    params1 += '"access_token":' + user.userID;
    params1 += '}';
    var json1 = JSON.parse(params1);
    $.post(service_url + "app/getFriendByName", json1,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    init_myfriend_div(result.data);
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

function addFriendRequest(userID)
{
    var params = '{';
    params += '"userid":"' + userID + '",';
    params += '"validmsg":"我是' + user.userName + '",';
    params += '"access_token":' + user.userID;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/addFriendsRequest", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    findFriend();
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

function confirmFriendRequest(userID, way)
{
    var params = '{';
    params += '"userid":"' + userID + '",';
    params += '"way":' + way + ',';
    params += '"access_token":' + user.userID;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/confirmOrDenyFriendRequest", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    init_friend();
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

//删除好友
function delFriend(userID)
{
    var params = '{';
    params += '"userid":"' + userID + '",';
    params += '"access_token":' + user.userID;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/delFriend", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    init_friend();
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

function showFriendName(userid, friendName)
{
    document.getElementById("friend_userid").value = userid;
    document.getElementById("friend_name").value = friendName;
    $('#updateFriendNameModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
    $('#updateFriendNameModal').modal('show');
}

function updateFriendName(userid)
{
    $("#f_update_friend_name").ajaxSubmit({
        type: 'post',
        url: 'app/updateFriendName',
        success: function(result) {
            result = getJSONObj(result);
            if (result == null)
            {
                return;
            }
            if (result.code == 200)
            {
                init_friend();
                $('#updateFriendNameModal').modal('hide');
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

function getUserName(userObj)
{
    var name = "";
    if (userObj.friendName != null && userObj.friendName != "" && userObj.friendName != "NULL")
    {
        name = userObj.friendName;
    }
    else if (userObj.nickName != null && userObj.nickName != "" && userObj.nickName != "NULL")
    {
        name = userObj.nickName;
    }
    else if (userObj.userName != null && userObj.userName != "" && userObj.userName != "NULL")
    {
        name = userObj.userName;
    }
    return name;
}

function getFriendName(userObj)
{
    if (userObj.friendName != null && userObj.friendName != "" && userObj.friendName != "NULL")
    {
        return "(" + userObj.friendName + ")";
    }
    else if (userObj.nickName != null && userObj.nickName != "" && userObj.nickName != "NULL")
    {
        return "(" + userObj.nickName + ")";
    }
    return "";
}

//获取好友模板
function getFriendTemplate(friendStatus, userTo)
{
    var template = "<img id='yaohe_image_url_img' style='height: 30px;width:30px;' src='{0}' ></img>"
            .format(service_url + userTo.userHead);
    template += "<span class='username'>{0}[{1}]</span>".format(userTo.userName,userTo.nickName);
    switch (friendStatus)
    {
        //不是好友
        case "0":
            template += "<a class='pull-right' href='javascript:addFriendRequest({0})'>添加</a>".format(userTo.userID);
            break;
            //请求状态
        case "1":
            if (userTo.invoker == user.userID)
            {
                template += "<a disabled='true' class='pull-right'>等待验证</a>";
            }
            else
            {
                template += "<a href='javascript:confirmFriendRequest({0},3)' class='pull-right'>拒绝</a>".format(userTo.userID);
                template += "<a href='javascript:confirmFriendRequest({0},2)' style='margin-right:10px;' class='pull-right'>接受</a>".format(userTo.userID);
            }
            break;
            //接受状态
        case "2":
            template += "<a href='javascript:showFriendName({0},{2})'><span id='id_friendName_{0}' class='username'>({1})</span></a>"
                    .format(userTo.userID,
                            userTo.friendName == null || userTo.friendName == "" ? "添加备注" : userTo.friendName,
                            userTo.friendName == null ? '""' : '"' + userTo.friendName + '"');
            template += "<a class='pull-right' href='javascript:delFriend({0})'>删除</a>".format(userTo.userID);
            break;
            //拒绝状态
        case "3":
            template += "<a class='pull-right' href='javascript:addFriendRequest({0})'>{1}</a>".format(userTo.userID,user.userID == userTo.invoker?"对方拒绝":"已拒绝");
            break;
    }
    return template;
}