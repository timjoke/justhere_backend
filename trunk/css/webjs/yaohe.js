//获取吆喝信息
function init_yaohe(pos)
{
    var seach_start_date = document.getElementById("begin_time").value;
    var seach_radius = document.getElementById("id_radius").value;

    if (seach_start_date == null || seach_start_date == "undefined" || seach_start_date == "")
    {
        alert("请输入查询的起始日期");
        return;
    }

    seach_radius = parseInt(seach_radius)*1000;
    if (seach_radius == null || seach_radius == "undefined" || isNaN(seach_radius) || seach_radius <= 0)
    {
        alert("请输入查询半径");
        return;
    }
    seach_radius+=1;
    var params = '{';
    params += '"access_token":' + user.userID + ',';
    params += '"userid":' + user.userID + ',';
    params += '"longitude":' + pos.lng + ',';
    params += '"latitude":' + pos.lat + ',';
//    params += '"longitude":"' + 0 + '",';
//    params += '"latitude":"' + 0 + '",';
//    params += '"radius":' + getRadiusByMapZoom() + ',';
    params += '"radius":' + seach_radius + ',';
    var m_name = "";
    switch (current_get_yaohe_type)
    {

        case "0":
        case "1":
        case "2":
            m_name = "app/getYaoheInfoByToUser";
            params += '"reply_status":' + current_get_yaohe_type + ',';
            break;
        case "3":
            m_name = "app/getYaoheInfoByUser";
            break;
        case "4":
            m_name = "app/getNearByYaoheInfo";
            break;
        default:
            m_name = "app/getNearByYaoheInfo";
            break;
    }

    params += '"last_time":"' + seach_start_date + '"';
    params += '}';
    var json = JSON.parse(params);

    $.post(service_url + m_name, json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    cleanMarker();
                    if (result.data["all_toself"])
                    {
                        for (var i = 0; i < result.data["all_toself"].length; i++)
                        {
                            var p = new AMap.LngLat(result.data["all_toself"][i].longitude, result.data["all_toself"][i].latitude);
                            var marker = new AMap.Marker({
                                map: mapObj,
                                position: p,
                                icon: getIconByUserID(result.data["all_toself"][i].userID),
                                offset: new AMap.Pixel(-10, -34),
                                data: result.data["all_toself"][i].yaoheID
                            });

                            markers.push(marker);
                            var itemDIV = document.createElement("div");
                            itemDIV.id = "id_yaohe_" + result.data["all_toself"][i].yaoheID;
                            itemDIV.innerHTML = init_yaoheInfoTemplate(result.data["all_toself"][i], true).join("");
                            leftDiv.appendChild(itemDIV);

                            AMap.event.addListener(marker, "click", function(e) {
                                leftYaohe_click(e.target.Sc.data);
                            });
                        }
                    }
                    if (result.data["all_radius"])
                    {
                        for (var i = 0; i < result.data["all_radius"].length; i++)
                        {
                            var p = new AMap.LngLat(result.data["all_radius"][i].longitude, result.data["all_radius"][i].latitude);
                            var marker = new AMap.Marker({
                                map: mapObj,
                                position: p,
                                icon: getIconByUserID(result.data["all_radius"][i].userID),
                                offset: new AMap.Pixel(-10, -34),
                                data: result.data["all_radius"][i].yaoheID
                            });

                            markers.push(marker);
                            var itemDIV = document.createElement("div");
                            itemDIV.id = "id_yaohe_" + result.data["all_radius"][i].yaoheID;
                            itemDIV.innerHTML = init_yaoheInfoTemplate(result.data["all_radius"][i], true).join("");
                            leftDiv.appendChild(itemDIV);

                            AMap.event.addListener(marker, "click", function(e) {
                                leftYaohe_click(e.target.Sc.data);
                            });
                        }
                    }
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

//发布吆喝click事件
function publishYaohe_click()
{
    $("#f_publishyaohe").ajaxSubmit({
        type: 'post',
        url: 'app/publishYaohe',
        success: function(result) {
            result = getJSONObj(result);
            if (result == null)
            {
                return;
            }
            if (result.code == 200)
            {
                cleanMarker();
                init_yaohe(mapObj.getCenter());
                alert("发布成功！");
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

//发表赞
function goodYaohe(yaoheid)
{
    var json_str = "{";
    json_str += '"yaoheid":"' + yaoheid + '",';
    json_str += '"access_token":' + user.userID;
    json_str += '}';
    var json = JSON.parse(json_str);
    $.post(service_url + "app/goodYaohe", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    $('.id_goodcount_' + yaoheid).each(function() {
                        //$(this)[0].innerText = parseInt($(this)[0].innerText) + 1;
                        if ($(this)[0].localName == "a")
                        {
                            $(this)[0].href = "javascript:ungoodYaohe({0});".format(yaoheid);
                            //$(this)[0].innerText = "取消赞";
                            //$(this)[0].children[0].innerText = parseInt($(this)[0].children[0].innerText) + 1;
                            $(this)[0].innerHTML = "取消赞(<span>{0}</span>)".format(parseInt($(this)[0].children[0].innerText) + 1);

                        }
                    });
                    leftYaohe_click(yaoheid);
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

//取消赞
function ungoodYaohe(yaoheid)
{
    var json_str = "{";
    json_str += '"yaoheid":"' + yaoheid + '",';
    json_str += '"access_token":' + user.userID;
    json_str += '}';
    var json = JSON.parse(json_str);
    $.post(service_url + "app/ungoodYaohe", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    $('.id_goodcount_' + yaoheid).each(function() {
                        if ($(this)[0].localName == "a")
                        {
                            $(this)[0].href = "javascript:goodYaohe({0});".format(yaoheid);
                            //$(this)[0].children[0].innerText = parseInt($(this)[0].children[0].innerText) - 1;
                            $(this)[0].innerHTML = "赞(<span>{0}</span>)".format(parseInt($(this)[0].children[0].innerText) - 1);
                        }
//                        else if ($(this)[0].localName == "span")
//                        {
//                            $(this)[0].innerText = parseInt($(this)[0].innerText) - 1;
//                        }

                    });
                    leftYaohe_click(yaoheid);
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

//点击右侧的吆喝，在地图上显示出详细吆喝信息来
function leftYaohe_click(yaoheid)
{
    for (var i = 0; i < markers.length; i++)
    {
        if (markers[i].Sc.data == yaoheid)
        {
            var json_str = "{";
            json_str += '"yaoheID":"' + yaoheid + '",';
            json_str += '"access_token":' + user.userID;
            json_str += '}';
            var json = JSON.parse(json_str);
            $.post(service_url + "app/getYaoheDetailsInfo", json,
                    function(result) {
                        result = getJSONObj(result);
                        if (result == null)
                        {
                            return;
                        }
                        if (result.code == 200)
                        {
                            mapObj.setCenter(markers[i].getPosition());
//                            var inforWindow = new AMap.InfoWindow({
//                                offset: new AMap.Pixel(0, -23),
//                                content: init_yaoheInfoTemplate(result.data, false).join("")
//                            });
                            infoWindow.setContent(createInfoWindow(init_yaoheInfoTemplate(result.data, false).join("")));
                            infoWindow.open(mapObj, markers[i].getPosition());
                        }
                        else
                        {
                            alert("code:" + result.code + ",message:" + result.message);
                        }
                    });
            break;
        }
    }
}

//打开评论modal
function showCommentModal(yaoheid, commentid, curr_userid)
{
    if (curr_userid == user.userID)
    {
        alert("自己不能评论自己！");
        return;
    }
    document.getElementById("comment_yaoheID").value = yaoheid;
    document.getElementById("comment_commentedID").value = commentid;
    $('#commentModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
    $('#commentModal').modal('show');
}

//发表评论
function saveComment() {
    $("#f_comment").ajaxSubmit({
        type: 'post',
        url: 'app/commentYaohe',
        success: function(result) {
            result = getJSONObj(result);
            if (result == null)
            {
                return;
            }
            if (result.code == 200)
            {
                var yaohe_id = document.getElementById("comment_yaoheID").value;
                var yaohe_status = document.getElementById("id_yaohestatus_" + yaohe_id);
                if (yaohe_status != null)
                {
                    yaohe_status.innerText = "已发现";
                }
                //更新右侧吆喝评论数
//                var commentcount_ele = document.getElementById("id_commentcount_" + yaohe_id);
//                commentcount_ele.innerText = parseInt(commentcount_ele.innerText) + 1;

                $('.id_commentcount_' + yaohe_id).each(function() {
                    $(this)[0].innerText = parseInt($(this)[0].innerText) + 1;
                });
                //更新地图上的吆喝信息
                leftYaohe_click(yaohe_id);
                $('#commentModal').modal('hide');
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

//根据userid获取不同颜色的地图标记
function getIconByUserID(userid)
{
    if (user.userID == userid)
    {
        return "http://api.iyaohe.cc/images/lvse.png";
    }
    return "http://webapi.amap.com/images/0.png";
}

//删除吆喝
function deleteYaohe(yaoheid)
{
    $('#deleteYaoheModal').modal('show').on('shown', function() {
        $("#btnDeleteYaohe").bind('click', function(e) {
            e.preventDefault();
            var json_str = "{";
            json_str += '"yaoheid":"' + yaoheid + '",';
            json_str += '"access_token":' + user.userID;
            json_str += '}';
            var json = JSON.parse(json_str);
            $.post(service_url + "app/deleteYaohe", json,
                    function(result) {
                        result = getJSONObj(result);
                        if (result == null)
                        {
                            return;
                        }
                        if (result.code == 200)
                        {
                            leftDiv.removeChild(document.getElementById("id_yaohe_" + yaoheid));
                            removeMarkerByYaoheid(yaoheid);
                            $('#deleteYaoheModal').modal('hide');
                        }
                        else
                        {
                            alert("code:" + result.code + ",message:" + result.message);
                        }
                    });
        });
    });
}

//移除地图上的marker标记
function removeMarkerByYaoheid(yaoheid)
{
    for (var i = 0; i < markers.length; i++)
    {
        if (markers[i].Sc.data == yaoheid)
        {
            markers[i].setMap(null);
            markers.pop(markers[i]);
            infoWindow.close();
            break;
        }
    }
}

function getYaoheStatus(status)
{
    var status_str = "";
    switch (status)
    {
        case "1":
            status_str = "未发现";
            break;
        case "2":
            status_str = "已发现";
            break;
    }
    return status_str;
}

function getRadiusByMapZoom()
{
    //var mapZoom = mapObj.getZoom();
    var radius = 1000 * 2000;
//    switch (mapZoom)
//    {
//        case 11:
//            
//            break;
//        case 12:
//            break;
//        default:
//            break;
//    }
    //radius = 500;
    return radius;
}

//初始化发布吆喝模板
function init_yaoheFormTemplate(position, address)
{
    yaoheFormTemplate = [];
    //yaoheFormTemplate.push("<b>发布吆喝信息</b>");
    yaoheFormTemplate.push("<form id='f_publishyaohe' method='post' action='#' style='margin:0px;'>");
    yaoheFormTemplate.push("<div class='well'>");
    yaoheFormTemplate.push("<div class='content_row'>");
    yaoheFormTemplate.push("<div class='row_left'>");
    yaoheFormTemplate.push("<p>标题</p>");
    yaoheFormTemplate.push("</div>");
    yaoheFormTemplate.push("<div class='row_right'>");
    yaoheFormTemplate.push("<textarea id='yaohe_content' style='width:250px;height:100px;' name='context' maxlength='200'></textarea>");
    yaoheFormTemplate.push("</div>");
    yaoheFormTemplate.push("</div>");
    yaoheFormTemplate.push("<div class='content_row'>");
    yaoheFormTemplate.push("<div class='row_left'>");
//    yaoheFormTemplate.push("<p>选择图片</p>");
    yaoheFormTemplate.push("</div>");
    yaoheFormTemplate.push("<div class='row_right'>");
    yaoheFormTemplate.push("<a href='javascript:showUploadFileModal(" + '"yaohe_image_id",false' + ")' style='margin-top: 5px;'>");
    yaoheFormTemplate.push("<img id='yaohe_image_id_img' style='height: 60px;width:100px;' alt='选择图片' ></img>");
    //yaoheFormTemplate.push("<canvas id='ctx' style='border:1px solid black;'></canvas>");
    yaoheFormTemplate.push("</a>");
    yaoheFormTemplate.push("</div>");
    yaoheFormTemplate.push("</div>");
    yaoheFormTemplate.push("<div class='content_row'>");
    //yaoheFormTemplate.push("<div class='row_left'>");
    //yaoheFormTemplate.push("<p>选择好友</p>");
    //yaoheFormTemplate.push("</div>");
    //yaoheFormTemplate.push("<div class='row_right'>");
    yaoheFormTemplate.push("<div style='display:inline-block;'><input class='username' type='checkbox' name='usersTo[]' value='0'/>所有人</div>");
    yaoheFormTemplate.push("<div style='display:inline-block;'><input class='username' type='checkbox' name='usersTo[]' value='{0}'/>自己</div>".format(user.userID));
    $.each(user.friends, function(k, v) {
        yaoheFormTemplate.push("<div style='display:inline-block;'><input class='username' name='usersTo[]' type='checkbox' value='{0}'/>{1}</div>".format(v.userID, getUserName(v)));
    });
    //yaoheFormTemplate.push("</div>");
    yaoheFormTemplate.push("</div>");

    yaoheFormTemplate.push("<div class='content_row' style='margin-top:10px;'>");
    yaoheFormTemplate.push("<img style='height:18px;vertical-align: middle;' src='images/addressIcon.png'></i>");
    yaoheFormTemplate.push("<textarea id='yaohe_content' style='width:250px;height:50px;display:inline;' name='address'>" + address + "</textarea>");
    yaoheFormTemplate.push("</div>");

    yaoheFormTemplate.push("</div>");
    yaoheFormTemplate.push("<div class='content_row'>");
    yaoheFormTemplate.push("<input type='hidden' name='longitude' value='" + position.lng + "'/>");
    yaoheFormTemplate.push("<input type='hidden' name='latitude' value='" + position.lat + "'/>");
    yaoheFormTemplate.push("<input type='hidden' name='radius' value='200'/>");
    yaoheFormTemplate.push("<input type='hidden' name='access_token' value='" + user.userID + "'/>");
    yaoheFormTemplate.push("<input id='yaohe_image_id' type='hidden' name='file_id'/>");
    yaoheFormTemplate.push("<input type='button' onclick='publishYaohe_click()' class='btn btn-primary pull-right' style='margin-top:5px;' value='发布'/>");
    yaoheFormTemplate.push("</div>");
    yaoheFormTemplate.push("</form>");
}

//初始化查看吆喝模板
function init_yaoheInfoTemplate(yaohe, hasADiv)
{
    var yaoheInfoTemplate = [];
    if (hasADiv)
    {
        //是否可以导航到地图上的信息
        yaoheInfoTemplate.push("<a href='javascript:leftYaohe_click({0});'>".format(yaohe.yaoheID));
    }
    //添加头像，用户名称和发布时间
    yaoheInfoTemplate.push("<div class='yaoheinfowell well'>");
    yaoheInfoTemplate.push("<div class='content_row yaoheinfo'>");
    yaoheInfoTemplate.push("<img id='yaohe_image_url_img' style='height: 30px;' src='{0}' ></img>".format(service_url + yaohe.userFrom.userHead));
    yaoheInfoTemplate.push("<span class='username'>{0}{1}</span>".format(yaohe.userFrom.userName, getFriendName(yaohe.userFrom)));

//判断该吆喝是否给自己
    var isToSelf = false;
    if (yaohe.usersTo != null)
    {
        for (var i = 0; i < yaohe.usersTo.length; i++)
        {
            if (user.userID == yaohe.usersTo[i].userID)
            {
                isToSelf = true;
                break;
            }
        }

    }
    if (isToSelf)
    {
        yaoheInfoTemplate.push("<span>&nbsp;&nbsp;状态:</span><span id='id_yaohestatus_{0}'>{1}</span>".format(yaohe.yaoheID, getYaoheStatus(yaohe.replyStatus)));
    }
    yaoheInfoTemplate.push("<span class='pull-right margin-right'>{0}</span>".format(yaohe.publish_date));
    yaoheInfoTemplate.push("</div>");

//添加内容
    yaoheInfoTemplate.push("<div class='content_row yaoheinfo'>");
    yaoheInfoTemplate.push("<b>{0}</b>&nbsp;&nbsp;(id={1})".format(yaohe.context, yaohe.yaoheID));
    yaoheInfoTemplate.push("</div>");

    if (!hasADiv)
    {
        yaoheInfoTemplate.push("<div class='content_row yaoheinfo'>");
        yaoheInfoTemplate.push("<img id='yaohe_image_url_img' style='height: 150px;' src='{0}' ></img>".format(service_url + yaohe.filepath));
        yaoheInfoTemplate.push("</div>");
    }

//添加地址
    yaoheInfoTemplate.push("<div class='content_row yaoheinfo'>");
    yaoheInfoTemplate.push("<img style='height:15px;vertical-align: top;' src='images/addressIcon.png'></i>");
    yaoheInfoTemplate.push("<span class='littlefont'>{0}</span>".format(yaohe.address));
    yaoheInfoTemplate.push("</div>");

//添加目标用户
    yaoheInfoTemplate.push("<div class='content_row yaoheinfo'>");
    if (yaohe.usersTo == null || yaohe.usersTo.length == 0 || (yaohe.usersTo != null && yaohe.usersTo.length > 0 && yaohe.usersTo[0].userTo == 0))
    {
        yaoheInfoTemplate.push("<div style='display:inline-block;'><span class='username'>所有人</span></div>");
    }
    else
    {
        $.each(yaohe.usersTo, function(k, v) {
            yaoheInfoTemplate.push("<div style='display:inline-block;'><span class='username'>{0}({1})</span></div>".format(
                    getUserName(v), v.replyStatus == 1 ? "未发现" : "已发现"));
        });
    }
    yaoheInfoTemplate.push("</div>");

//添加赞和评论链接
    yaoheInfoTemplate.push("<div class='content_row'>");
    if (yaohe.goodBySelf == 0)
    {
        yaoheInfoTemplate.push("<a class='margin-left id_goodcount_{0}' href='javascript:goodYaohe({0});'>赞(<span>{1}</span>)</a>".
                format(yaohe.yaoheID, yaohe.goodCount ? yaohe.goodCount : yaohe.goods.length));
    }
    else
    {
        yaoheInfoTemplate.push("<a class='margin-left id_goodcount_{0}' href='javascript:ungoodYaohe({0});'>取消赞(<span>{1}</span>)</a>".
                format(yaohe.yaoheID, yaohe.goodCount ? yaohe.goodCount : yaohe.goods.length));
    }

    yaoheInfoTemplate.push("<a class='margin-left' href='javascript:showCommentModal({0},0);'>评论(<span class='id_commentcount_{0}'>{1}</span>)</a>".
            format(yaohe.yaoheID, yaohe.comments.length));

    if (hasADiv && yaohe.userID == user.userID)
    {
        yaoheInfoTemplate.push("<a class='margin-right pull-right' href='javascript:deleteYaohe({0});'>删除</a>".
                format(yaohe.yaoheID));
    }
    yaoheInfoTemplate.push("</div>");


    //添加赞的好友
    if (!hasADiv && yaohe.goods.length > 0)
    {
        yaoheInfoTemplate.push("<div id='goodDiv' class='content_row margin5 border-top'>");
        for (var i = 0; i < yaohe.goods.length; i++)
        {
            yaoheInfoTemplate.push("<a href='javascript:alert({0});'>".
                    format(yaohe.goods[i].goodID));
            yaoheInfoTemplate.push("<img style='height: 30px;' src='{0}' ></img>&nbsp;{1}".
                    format(service_url + yaohe.goods[i].userHead, getUserName(yaohe.goods[i])));
            yaoheInfoTemplate.push("</a>");
        }
        yaoheInfoTemplate.push("</div>");
    }

    //添加评论
    if (!hasADiv && yaohe.comments.length > 0)
    {
        yaoheInfoTemplate.push("<div id='commentDiv' class='content_row'>");
        for (var i = 0; i < yaohe.comments.length; i++)
        {
            if (yaohe.comments[i].commentedID == 0)
            {
                yaoheInfoTemplate.push(getCommentItemTemplate(deep, yaohe.yaoheID, yaohe.comments[i]));
                yaoheInfoTemplate.push(getCommentTemplate(yaohe.yaoheID, yaohe.comments, yaohe.comments[i]));
            }
        }
        yaoheInfoTemplate.push("</div>");
    }

    yaoheInfoTemplate.push("</div>");
    if (hasADiv)
    {
        yaoheInfoTemplate.push("</a>");
    }

    //yaoheInfoTemplate.push("</div>");
    return yaoheInfoTemplate;
}

var deep = 0;
//递归获取评论
function getCommentTemplate(yaoheid, comments, currentComment)
{
    deep++;
    var yaoheCommentTemplate = [];
    for (var i = 0; i < comments.length; i++)
    {
        if (comments[i].commentedID == currentComment.commentID)
        {
            yaoheCommentTemplate.push(getCommentItemTemplate(deep, yaoheid, comments[i]));
            yaoheCommentTemplate.push(getCommentTemplate(yaoheid, comments, comments[i]));
        }
    }
    deep--;
    return yaoheCommentTemplate.join("");
}

function getCommentItemTemplate(deep, yaoheid, curr_comment)
{
    var commentItem = [];
    commentItem.push("<div style='margin:3px;'>");
    for (var j = 0; j < deep; j++)
    {
        commentItem.push("<span style='margin:0 5px;'>&nbsp;&nbsp;</span>");
    }

    commentItem.push("<img style='height: 30px;' src='{0}' />".format(curr_comment.userHead));
    commentItem.push("<a class='margin-left;' href='javascript:showCommentModal({0},{1},{2});'>"
            .format(yaoheid, curr_comment.commentID, curr_comment.userFromID));
    //commentItem.push("<div>");
    commentItem.push("<span>&nbsp;{0}:&nbsp;{1}</span>".format(getUserName(curr_comment), curr_comment.content));
    commentItem.push("<span style='display:none;'>&nbsp;{0}</span>".format(curr_comment.createtime));
    //commentItem.push("</div>");
    commentItem.push("</a>");
    commentItem.push("</div>");

    return commentItem.join("");
}