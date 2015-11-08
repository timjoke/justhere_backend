//获取好友未读消息数
function getUnreadCount() {
    var params = '{';
    params += '"access_token":' + user.userID;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/getUnreadCount", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    if (result.data.count_yaohe && parseInt(result.data.count_yaohe) > 0)
                    {
                        unreadmsgElement.innerText = result.data.count_yaohe;
                        unreadmsgElement.className = "unread";
                    }
                    else
                    {
                        unreadmsgElement.className = "visible-unreadcount";
                    }

//                    if (result.data.count_friend && parseInt(result.data.count_friend) > 0)
//                    {
//                        unreadfriendElement.innerText = result.data.count_friend;
//                        unreadfriendElement.className = "unread";
//                    }
//                    else
//                    {
//                        unreadfriendElement.className = "visible-unreadcount";
//                    }
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
                getUnreadTimer = window.setTimeout(function() {
                    getUnreadCount();
                }, 30000);
            });
}

//弹出我的消息modal
function showMsgModal() {
    var params = '{';
    params += '"access_token":' + user.userID;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/getUnreadMsg", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    $('#unreadMsgModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
                    $('#unreadMsgModal').modal('show');
                    var msgDiv = document.getElementById("msgDiv");
                    msgDiv.innerHTML = "";
                    for (var i = 0; i < result.data.length; i++)
                    {
                        var itemDIV = document.createElement("div");
                        itemDIV.className = "friendsDiv";
                        itemDIV.innerHTML = getMsgTemplate(result.data[i]);
                        msgDiv.appendChild(itemDIV);
                    }
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

//已读用户消息
function readMsg(msgID)
{
    var params = '{';
    params += '"msgid":"' + msgID + '",';
    params += '"access_token":' + user.userID;
    params += '}';
    var json = JSON.parse(params);
    $.post(service_url + "app/readMsg", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    showMsgModal();
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

//获取消息模板
function getMsgTemplate(msg_data)
{
    var template = "<img id='yaohe_image_url_img' style='height: 30px;width:30px;' src='{0}' ></img>"
            .format(service_url + msg_data.userHead);
    //template += "<span class='username'>{0}</span>".format(msg_data.userName);
    template += "<span>{0}&nbsp;&nbsp;&nbsp;&nbsp;{1}</span>".format(getMessageTemplateByType(msg_data), msg_data.createtime);
    switch (msg_data.status)
    {
        //已读
        case "0":
            template += "<a class='pull-right' href='javascript:readMsg({0})'>未读</a>".format(msg_data.msgID);
            break;
            //未读
        case "1":
            template += "<a disabled='true' class='pull-right'>已读</a>";
            break;
    }
    return template;
}

//获取消息模板
function getMessageTemplateByType(msg_data)
{
    var template_str = "";
    var displayName = "";
    if (msg_data.friendName != null && msg_data.friendName != "")
        displayName = msg_data.friendName;
    else if (msg_data.nickName != null && msg_data.nickName != "")
        displayName = msg_data.nickName;
    else
        displayName = msg_data.userName;

    switch (msg_data.type)
    {
        case "1":
            template_str = displayName + " 在 {0} 给你留了一个信息({1})".format(msg_data.content,msg_data.data);
            break;
        case "2":
            template_str = displayName + " 评论了你的信息({0})".format(msg_data.data);
            break;
        case "3":
            template_str = displayName + " 请求加你为好友";
            break;
        case "4":
            template_str = displayName + " 赞了你的信息({0})".format(msg_data.data);
            break;
        case "5":
            template_str = displayName + " 在 {0} 发现了你的信息({1})".format(msg_data.content,msg_data.data);
            break;
        case "6":
            template_str = displayName + " 接受你的加好友请求";
            break;
        case "7":
            template_str = displayName + " 拒绝你的加好友请求";
            break;
        case "8":
            template_str = displayName + " 回复了你的评论({0})".format(msg_data.data);
            break;
        default:
            template_str = displayName + " 给你留了一个信息";
            break;
    }
    return template_str;
}