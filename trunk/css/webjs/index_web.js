var user = null;//登陆用户
var currentMarker = null;//地图上当前鼠标点击的位置
var infoWindow = null;//地图上的信息窗体
var yaoheFormTemplate = [];//创建吆喝模板
var service_url = "http://api.iyaohe.cc/";//接口地址
var mapObj;//高德地图容器
var position; //= new AMap.LngLat(116.397428, 39.90923);//天安门坐标
var markers = [];//地图上的所有吆喝标记
var leftDiv = null;//右边栏位
var getUnreadTimer = null;//获取用户未读消息的timer
var unreadmsgElement;//显示未读消息的DIV，在我的消息顶部
var unreadfriendElement;//显示未读消息的DIV,在我的好友顶部

//文档初始化完成
$(document).ready(function() {

    //service_url = window.location.href.substr(0, window.location.href.length - 13);
    leftDiv = document.getElementById("leftPage");
    unreadmsgElement = document.getElementById("unreadmsg");
    unreadfriendElement = document.getElementById("unreadfriend");
    init();

//初始化城市数据
    init_city_data(i);

//    $('#loadingModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
//    $('#loadingModal').modal('show');
});

//初始化登陆
function init()
{
    user = JSON.parse(window.localStorage.getItem("user"));
    if (user == null)
    {
        showLoginModal();
    }
    else
    {
        init_user();
    }
}

function refresh()
{
    init_user();
}

var isURL = null;
var elementID = null;
//弹出上传文件modal
function showUploadFileModal(elementID, isURL)
{
    this.isURL = isURL;
    this.elementID = elementID;
    $('#uploadFileModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
    $('#uploadFileModal').modal('show');
}

//上传文件
function uploadfile()
{
    if (document.getElementById("id_uploadfile").files.length == 0)
    {
        alert("请选择文件！");
        return;
    }
    var m_name = "";
    if (isURL)
    {
        m_name = "app/uploadHeadImage";
    }
    else
    {
        m_name = "app/uploadFile";
    }
    $("#f_login").ajaxSubmit({
        type: 'post',
        url: m_name,
        clearForm: true,
        beforeSend: function() {
            //progress.show();
            //var percentVal = "0%";
            //bar.css("width", percentVal);
            //$(".percent").html(percentVal);
            //$(".status").html("上传中");
            $('#loadingModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
            $('#loadingModal').modal('show');
        },
        uploadProgress: function(event, pos, total, percentComplete)
        {
            var percentVal = percentComplete + "%";
            $(".bar").width(percentVal);
            $(".percent").html(percentComplete + "%");
        },
        complete: function(data)
        {
            $('#loadingModal').modal('hide');
        },
        success: function(result) {
            $(".bar").width("0%");
            $(".percent").html("0%");
            //$(".status").html("");
            result = getJSONObj(result);
            if (result == null)
            {
                return;
            }
            if (result.code == 200)
            {
                if (isURL)
                {
                    document.getElementById(elementID + "_img").src = service_url + result.data.image_url;
                    document.getElementById("userheadimg").src = service_url + result.data.image_url;
                    user.userHead = result.data.image_url;
                    window.localStorage.setItem("user", JSON.stringify(user));
                    document.getElementById(elementID).value = result.data.image_url;
                }
                else
                {
                    document.getElementById(elementID + "_img").src = service_url + result.data.image_url;
                    document.getElementById(elementID).value = result.data.file_id;
                }
                $('#uploadFileModal').modal('hide');
            }
            else
            {
                //$(".status").html("上传失败<br/>" + "code:" + result.code + ",message:" + result.message);
                alert("上传失败!返回：" + "code:" + result.code + ",message:" + result.message);
            }

        },
        error: function(XmlHttpRequest, textStatus, errorThrown) {
            alert(XmlHttpRequest + "," + textStatus + "," + errorThrown);
        }
    });
}

//转换json字符串到js对象
function getJSONObj(jsonStr)
{
    try
    {
        result = JSON.parse(jsonStr);
    } catch (e)
    {
        alert(jsonStr);
        return null;
    }
    return result;
}

//初始化我的好友
function getAllQuestion()
{
    var json = JSON.parse('{"access_token":' + user.userID + '}');
    $.post(service_url + "app/getAllQuestion", json,
            function(result) {
                result = getJSONObj(result);
                if (result == null)
                {
                    return;
                }
                if (result.code == 200)
                {
                    $('#questionsModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
                    $('#questionsModal').modal('show');
//                    var questionsDiv = document.getElementById("id_all_questions");
//                    questionsDiv.innerHTML = "";
                    var list_table = document.getElementById("list_table");
                    //删除旧数据
                    var rows = list_table.rows.length;
                    while (list_table.rows.length > 1)
                    {
                        list_table.deleteRow(1);
                    }
                    for (var i = 0; i < result.data.length; i++)
                    {
                        var tr = document.createElement("tr");
//                        var itemDIV = document.createElement("div");
//                        itemDIV.className = "friendsDiv";
                        var tr_str = "";
                        tr_str += "<td>{0}</td>".format(result.data[i].questionID);
                        tr_str += "<td>{0}</td>".format(result.data[i].questionDesc);
                        tr_str += "<td>{0}</td>".format(result.data[i].userName);
                        tr_str += "<td>{0}</td>".format(result.data[i].deviceInfo);
                        tr_str += "<td>{0}</td>".format(result.data[i].osInfo);
                        tr_str += "<td>{0}</td>".format(result.data[i].appInfo);
                        tr_str += "<td>{0}</td>".format(result.data[i].createtime);
                        tr.innerHTML = tr_str;
                        list_table.appendChild(tr);
                    }
                }
                else
                {
                    alert("code:" + result.code + ",message:" + result.message);
                }
            });
}

/** 格式化输入字符串**/
//用法: "hello{0}".format('world')；返回'hello world'
String.prototype.format = function() {
    var args = arguments;
    return this.replace(/\{(\d+)\}/g, function(s, i) {
        return args[i];
    });
}

//将num补足n位
function pad(num, n) {
    var len = num.toString().length;
    while (len < n) {
        num = "0" + num;
        len++;
    }
    return num;
}

var current_get_yaohe_type = 0;
function radio_click(obj)
{
    if (current_get_yaohe_type == obj.value)
    {
        return;
    }
    current_get_yaohe_type = obj.value;
    init_yaohe(mapObj.getCenter());
}
