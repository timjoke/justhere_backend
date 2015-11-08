<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <title>post_request</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="jquery-1.8.1.min.js" ></script>
        <style>
            .item{margin-top: 10px;}
            .value{width: 800px;}
        </style>
    </head>
    <body>
        <div>
            <H1>模拟HTTP POST请求</H1>
            <input type="button" value="增加参数" id="btnAdd" onclick="btnAdd()"/>
            <input type="button" value="清空参数" id="btnAdd" onclick="btnClearParam()"/>
            <br/><br/>
            <a href="http://www.sojson.com/" target="_blank">json格式化</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=init" target="_blank">init</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=register" target="_blank">register</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=validPhone" target="_blank">validPhone</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=finishUserInfo" target="_blank">finishUserInfo</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=login" target="_blank">login</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=GetNearByYaoheInfo" target="_blank">GetNearByYaoheInfo</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=GetNearYaoheInfo" target="_blank">GetNearYaoheInfo</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=GetSelfYaoheInfo" target="_blank">GetSelfYaoheInfo</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=GetYaoheDetailsInfo" target="_blank">GetYaoheDetailsInfo</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=PublishYaohe" target="_blank">PublishYaohe</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=CommentYaohe" target="_blank">CommentYaohe</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=GoodYaohe" target="_blank">GoodYaohe</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=UngoodYaohe" target="_blank">UngoodYaohe</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=GetYaoheInfoByUser" target="_blank">GetYaoheInfoByUser</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=getUnreadCount" target="_blank">getUnreadCount</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=getUnreadMsg" target="_blank">getUnreadMsg</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=getAllFriends" target="_blank">getAllFriends</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=AddFriendsRequest" target="_blank">AddFriendsRequest</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=ConfirmOrDenyFriendRequest" target="_blank">ConfirmOrDenyFriendRequest</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=ReadMsg" target="_blank">ReadMsg</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=UpdateFriendName" target="_blank">UpdateFriendName</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=DelFriend" target="_blank">DelFriend</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=AddQuestion" target="_blank">AddQuestion</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=LoginOut" target="_blank">LoginOut</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=ChangeLoginStatus" target="_blank">ChangeLoginStatus</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=LocationTest" target="_blank">LocationTest</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=GetUserFullInfo" target="_blank">getUserFullInfo</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=GetValidCode" target="_blank">getValidCode</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=getOtherFriends" target="_blank">getOtherFriends</a>
            <a href="http://api.iyaohe.cc/ptest.php?m=savePassword" target="_blank">savePassword</a>
            <br/>
            <br/>
        </div>
        <hr>
        <div style="margin-left: 50px">
            POST地址：<input class="value" type="text" id="addressuri"/>
            <input type="button" value="请求" id="btnRequest" onclick="btnRequest()" style="width: 90px;margin-left: 20px;"/>
        </div>
        <hr>
        <div id="content" style="margin-left: 50px">
            <div class="item">
                <input type="text" id="name1"/>
                <input class="value" type="text" id="value1"/>
            </div>

            <div class="item">
                <input type="text" id="name2"/>
                <input class="value" type="text" id="value2"/>
            </div>

            <div class="item">
                <input type="text" id="name3"/>
                <input class="value" type="text" id="value3"/>
            </div>

            <div class="item">
                <input type="text" id="name4"/>
                <input class="value" type="text" id="value4"/>
            </div>

            <div class="item">
                <input type="text" id="name5"/>
                <input class="value" type="text" id="value5"/>
            </div>

            <div class="item">
                <input type="text" id="name6"/>
                <input class="value" type="text" id="value6"/>
            </div>

            <div class="item">
                <input type="text" id="name7"/>
                <input class="value" type="text" id="value7"/>
            </div>

            <div class="item">
                <input type="text" id="name8"/>
                <input class="value" type="text" id="value8"/>
            </div>

            <div class="item">
                <input type="text" id="name9"/>
                <input class="value" type="text" id="value9"/>
            </div>

            <div class="item">
                <input type="text" id="name10"/>
                <input class="value" type="text" id="value10"/>
            </div>
        </div>
        <hr>
        <div style="margin-left: 50px">
            <div>
                <input type="button" value="清空结果" id="btnAdd" onclick="btnClearResult()"/>
            </div>
            <textarea id="textResult" style="width:963px;height:150px;"/></textarea>
    </div>
    <script>
        var contentDiv = document.getElementById("content");
        var textResult = document.getElementById("textResult");
        var itemTemplate = '<input type="text" id="name3"/>';
        itemTemplate += ' <input class="value" type="text" id="value3"/>';

        var $_GET = (function () {
            var url = window.document.location.href.toString();
            var u = url.split("?");
            if (typeof (u[1]) == "string") {
                u = u[1].split("&");
                var get = {};
                for (var i in u) {
                    var j = u[i].split("=");
                    get[j[0]] = j[1];
                }
                return get;
            } else {
                return {};
            }
        })();

        init();

        function init()
        {
            var methodName = $_GET["m"];
            if (!methodName)
            {
                methodName = "login";
            }
            document.getElementById("addressuri").value = "http://api.iyaohe.cc/app/" + methodName;
            methodName = methodName.toLowerCase();
            switch (methodName)
            {
                case "init":
                    document.getElementById("name1").value = "os_type";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "version_id";
                    document.getElementById("value2").value = "1";
                    document.getElementById("name3").value = "version_mini_id";
                    document.getElementById("value3").value = "2";
                    break;
                case "register":
                    document.getElementById("name1").value = "username";
                    document.getElementById("value1").value = "\"cc\"";
                    document.getElementById("name2").value = "pwd";
                    document.getElementById("value2").value = "\"123\"";
                    document.getElementById("name3").value = "";
                    document.getElementById("value3").value = "";
                    break;
                case "validphone":
                    document.getElementById("name1").value = "phone";
                    document.getElementById("value1").value = "\"18888888888\"";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    document.getElementById("name3").value = "";
                    document.getElementById("value3").value = "";
                    break;
                case "finishuserinfo":
                    document.getElementById("name1").value = "userid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "phone";
                    document.getElementById("value2").value = "\"18888888888\"";
                    document.getElementById("name3").value = "email";
                    document.getElementById("value3").value = "\"cc@iyaoheinfo.com\"";
                    document.getElementById("name4").value = "sex";
                    document.getElementById("value4").value = "0";
                    document.getElementById("name5").value = "username";
                    document.getElementById("value5").value = "\"cc\"";
                    document.getElementById("name6").value = "pwd";
                    document.getElementById("value6").value = "\"123\"";
                    document.getElementById("name7").value = "image_url";
                    document.getElementById("value7").value = "\"http://baidu.com\"";
                    document.getElementById("name8").value = "os_type";
                    document.getElementById("value8").value = "1";
                    document.getElementById("name9").value = "modeldes";
                    document.getElementById("value9").value = "\"IPhone 5s\"";
                    document.getElementById("name10").value = "access_token";
                    document.getElementById("value10").value = "\"\"";
                    break;
                case "login":
                    document.getElementById("name1").value = "name";
                    document.getElementById("value1").value = "\"18611691649\"";
                    document.getElementById("name2").value = "pwd";
                    document.getElementById("value2").value = "\"062694\"";
                    document.getElementById("name3").value = "";
                    document.getElementById("value3").value = "";
                    break;
                case "getnearbyyaoheinfo":
                    document.getElementById("name1").value = "latitude";
                    document.getElementById("value1").value = "\"39.90923\"";
                    document.getElementById("name2").value = "longitude";
                    document.getElementById("value2").value = "\"116.397428\"";
                    document.getElementById("name3").value = "radius";
                    document.getElementById("value3").value = "100";
                    document.getElementById("name4").value = "ids_list";
                    document.getElementById("value4").value = "[1,2,3]";
                    document.getElementById("name5").value = "last_time";
                    document.getElementById("value5").value = "\"2014-06-01\"";
                    document.getElementById("name6").value = "access_token";
                    document.getElementById("value6").value = "\"\"";
                    break;
                case "getnearyaoheinfo":
                    document.getElementById("name1").value = "latitude";
                    document.getElementById("value1").value = "\"39.90923\"";
                    document.getElementById("name2").value = "longitude";
                    document.getElementById("value2").value = "\"116.397428\"";
                    document.getElementById("name3").value = "radius";
                    document.getElementById("value3").value = "100";
                    document.getElementById("name4").value = "ids_list";
                    document.getElementById("value4").value = "[1,2,3]";
                    document.getElementById("name5").value = "last_time";
                    document.getElementById("value5").value = "\"2015-01-25\"";
                    document.getElementById("name6").value = "access_token";
                    document.getElementById("value6").value = "\"\"";
                    document.getElementById("name7").value = "page";
                    document.getElementById("value7").value = "1";
                    break;
                case "getselfyaoheinfo":
                    document.getElementById("name1").value = "ids_list";
                    document.getElementById("value1").value = "[1,2,3]";
                    document.getElementById("name2").value = "page";
                    document.getElementById("value2").value = "1";
                    document.getElementById("name3").value = "last_time";
                    document.getElementById("value3").value = "\"2014-06-01\"";
                    document.getElementById("name4").value = "update_time";
                    document.getElementById("value4").value = "\"2015-01-25\"";
                    document.getElementById("name5").value = "access_token";
                    document.getElementById("value5").value = "\"\"";
                    break;
                case "getyaohedetailsinfo":
                    document.getElementById("name1").value = "yaoheID";
                    document.getElementById("value1").value = "6";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "publishyaohe":
                    document.getElementById("name1").value = "context";
                    document.getElementById("value1").value = "\"北京爱吆喝信息技术有限公司\"";
                    document.getElementById("name2").value = "address";
                    document.getElementById("value2").value = "\"北京市朝阳区常营地区北京像素南61913\"";
                    document.getElementById("name3").value = "longitude";
                    document.getElementById("value3").value = "\"41.3\"";
                    document.getElementById("name4").value = "latitude";
                    document.getElementById("value4").value = "\"116.412\"";
                    document.getElementById("name5").value = "radius";
                    document.getElementById("value5").value = "\"200\"";
                    document.getElementById("name6").value = "usersTo";
                    document.getElementById("value6").value = "[1,2,3]";
                    document.getElementById("name7").value = "file_id";
                    document.getElementById("value7").value = "1";
                    document.getElementById("name8").value = "access_token";
                    document.getElementById("value8").value = "\"\"";
                    break;
                case "commentyaohe":
                    document.getElementById("name1").value = "yaoheID";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "commentedID";
                    document.getElementById("value2").value = "1";
                    document.getElementById("name3").value = "content";
                    document.getElementById("value3").value = "\"楼主很赞，顶一个！\"";
                    document.getElementById("name4").value = "access_token";
                    document.getElementById("value4").value = "\"\"";
                    break;
                case "getyaoheinfobyuser":
                    document.getElementById("name1").value = "userid";
                    document.getElementById("value1").value = "2";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "getunreadcount":
                    document.getElementById("name1").value = "access_token";
                    document.getElementById("value1").value = "\"\"";
                    break;
                case "getunreadmsg":
                    document.getElementById("name1").value = "access_token";
                    document.getElementById("value1").value = "\"\"";
                    break;
                case "getallfriends":
                    document.getElementById("name1").value = "access_token";
                    document.getElementById("value1").value = "\"\"";
                    break;
                case "addfriendsrequest":
                    document.getElementById("name1").value = "userid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "validmsg";
                    document.getElementById("value2").value = "\"我是cc\"";
                    document.getElementById("name3").value = "access_token";
                    document.getElementById("value3").value = "\"\"";
                    break;
                case "readmsg":
                    document.getElementById("name1").value = "msgid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "updatefriendname":
                    document.getElementById("name1").value = "userid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "friendName";
                    document.getElementById("value2").value = "\"cc\"";
                    document.getElementById("name3").value = "access_token";
                    document.getElementById("value3").value = "\"\"";
                    break;
                case "delfriend":
                    document.getElementById("name1").value = "userid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "confirmordenyfriendrequest":
                    document.getElementById("name1").value = "userid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "way";
                    document.getElementById("value2").value = "2";
                    document.getElementById("name3").value = "access_token";
                    document.getElementById("value3").value = "\"\"";
                    break;
                case "goodyaohe":
                    document.getElementById("name1").value = "yaoheid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "ungoodyaohe":
                    document.getElementById("name1").value = "yaoheid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "loginout":
                    document.getElementById("name1").value = "access_token";
                    document.getElementById("value1").value = "\"\"";
                    break;
                case "changeloginstatus":
                    document.getElementById("name1").value = "login_status";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "addquestion":
                    document.getElementById("name1").value = "desc";
                    document.getElementById("value1").value = "\"发布信息地址获取不了\"";
                    document.getElementById("name2").value = "device_info";
                    document.getElementById("value2").value = "\"中兴V880\"";
                    document.getElementById("name3").value = "os_info";
                    document.getElementById("value3").value = "\"android V4.30319\"";
                    document.getElementById("name4").value = "app_info";
                    document.getElementById("value4").value = "\"V2.1\"";
                    document.getElementById("name5").value = "access_token";
                    document.getElementById("value5").value = "\"\"";
                    break;
                case "locationtest":
                    document.getElementById("name1").value = "arr";
                    document.getElementById("value1").value = '[{"lng":"116.397428","lat":"39.90923","time":"2014-10-27 00:00:00"}]';
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "getuserfullinfo":
                    document.getElementById("name1").value = "userid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "getvalidcode":
                    document.getElementById("name1").value = "telno";
                    document.getElementById("value1").value = "18611691649";
                    document.getElementById("name2").value = "is_telno_change";
                    document.getElementById("value2").value = "0";
                    document.getElementById("name3").value = "access_token";
                    document.getElementById("value3").value = "\"\"";
                    break;
                case "getotherfriends":
                    document.getElementById("name1").value = "userid";
                    document.getElementById("value1").value = "1";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;
                case "savepassword":
                    document.getElementById("name1").value = "password";
                    document.getElementById("value1").value = "\"\"";
                    document.getElementById("name2").value = "access_token";
                    document.getElementById("value2").value = "\"\"";
                    break;

                default:
                    document.getElementById("name1").value = "name";
                    document.getElementById("value1").value = "\"cc\"";
                    document.getElementById("name2").value = "pwd";
                    document.getElementById("value2").value = "\"123\"";
                    document.getElementById("name3").value = "";
                    document.getElementById("value3").value = "";
            }

        }

        function btnAdd()
        {
            var newDiv = document.createElement("div");
            newDiv.className = "item";
            newDiv.innerHTML = itemTemplate;
            contentDiv.appendChild(newDiv);
        }

        function btnClearParam()
        {
            //document.getElementById("addressuri").value = "";

            for (var i = 0; i < contentDiv.children.length; i++)
            {
                contentDiv.children[i].children[0].value = "";
                contentDiv.children[i].children[1].value = "";
            }
        }

        function btnClearResult()
        {
            textResult.value = "";
        }

        function btnRequest()
        {
            var json_str = "{";
            var request_uri = document.getElementById("addressuri").value;
            if (request_uri == "")
            {
                alert("请输入请求的地址！");
                return;
            }
            for (var i = 0; i < contentDiv.children.length; i++)
            {
                var name = contentDiv.children[i].children[0].value;
                var value = contentDiv.children[i].children[1].value;
                if (name != "")
                {
                    json_str += '"' + name + '":' + value;
                    json_str += ',';
                }
                else
                {
                    break;
                }
            }
            json_str = json_str.substr(0, json_str.length - 1);
            json_str += '}';
            var json = JSON.parse(json_str);
            $.post(request_uri, json,
                    function (result) {
                        //var jj = JSON.parse(result);
                        textResult.value = result;
                    });
        }
    </script>
</body>
</html>
