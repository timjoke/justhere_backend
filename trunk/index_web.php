<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="css/lib/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="css/stylesheets/theme.css"/>
        <link rel="stylesheet" href="css/lib/font-awesome/css/font-awesome.css"/>

        <link rel="stylesheet" href="css/webcss.css"/>
        <script src="css/lib/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="css/lib/jquery.ui.js" type="text/javascript"></script>
        <script src="css/lib/jquery.form.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/mapinfowindow.css" />

        <title>恰好WEB</title>
    </head>

    <body>
        <div class="navbar">
            <div class="navbar-inner">
                <ul class="nav pull-right">
                    <li>
                        <a href="javascript:refresh();" style="width: 60px;">刷新页面</a>
                    </li>
                    <li>
                        <a href="javascript:getAllQuestion();" style="width: 60px;">问题反馈</a>
                    </li>
                    <li>
                        <div id="unreadfriend" class="visible-unreadcount">1</div>
                        <a href="javascript:showFriendsModal();" style="width: 60px;">我的好友</a>
                    </li>
                    <li>
                        <div id="unreadmsg" class="visible-unreadcount">1</div>
                        <a href="javascript:showMsgModal();" style="width: 60px;">我的消息</a>
                    </li>
                    <li>
                        <a style="padding: 0px;" href="javascript:showFinishUserModal();">
                            <img id="userheadimg" style="max-height: 41px;max-width: 50px;" alt="头像" ></img>
                        </a>
                    </li>
                    <li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"></i> <span id="username"></span>&nbsp;:&nbsp;<span id="userid"></span>
                            <i class="icon-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="javascript:loginOut();">切换用户</a></li>
                        </ul>
                    </li>
                </ul>
                <a class="brand"><span class="first">恰好后台测试系统</span></a>
            </div>
        </div>
        <div id="content" class="content">
            <div>
                <div  id="map_container_tools" class="navbar-inner">
                    <ul id="map_left_ul" class="nav" style="padding: 9px;">
                        <li id="fat-menu" class="dropdown">
                            <a href="javascript:showOrHideCitySelect();" role="button" class="dropdown-toggle">
                                <span id="id_current_city">北京市</span>
                                <i class="icon-caret-down"></i>
                            </a>
                        </li>
                        <li>
                            <input type="radio" name="yaohe_type" onclick="radio_click(this)" value="4" checked="true"/> 全部
                            <input type="radio" name="yaohe_type" onclick="radio_click(this)" value="3"/> 我留的
                            <input type="radio" name="yaohe_type" onclick="radio_click(this)" value="0"/> 给我留的
                            <input type="radio" name="yaohe_type" onclick="radio_click(this)" value="1"/> 未发现的
                            <input type="radio" name="yaohe_type" onclick="radio_click(this)" value="2"/> 已发现的
                        </li>
                        <li>
                            <div style="margin-top:-10px;">
                                <input type="text" class="margin5" style="width:120px;" placeholder="请输入开始日期" id="begin_time"/>
                                <input type="text" class="margin5" style="width:120px;" placeholder="请输入半径(单位千米)" id="id_radius" value="10"/>
                                <a class="btn" href="javascript:search();" style="width: 60px;margin-top: 0px;">查询</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class='container' id="mapcontainer">

                </div>
            </div>
            <div class="leftPage" id="leftPage">
                <div style="clear:both"></div>
            </div>

            <!--        切换城市-->
            <div id="city-box" class="cityContainer J_cityContainer">
                <div class="city-list-item city-list-title">
                    <span>选择城市</span>
                    <a class="city-list-close-btn"></a>
                </div>
                <div class="city-list-item city-list-hot J_hotCities">

                </div>
                <!-- <div class="city-list-item city-list-pano">
                    <span>街景城市</span>
                    <div class="city-list-pano-content J_panoCities">
            
                    </div>
                    <div class="pano-icon"></div>
                </div> -->
                <div class="city-list-item city-list-srh">
                    <input type="text" class="city-list-ipt city-srh-ipt J_citySearchIpt ui-autocomplete-input" id="city-srh-ipt"
                           autocomplete="off"/>
                    <input type="button" class="city-list-ipt city-srh-btn" id="city-srh-btn" value="搜索城市"/>
                </div>
                <div class="city-list-item city-list-content">
                    <span>城市列表</span>
                    <div class="city-scroll scroll-pane" style="overflow: hidden; padding: 0px; width: 400px;">
                        <div class="jspContainer" style="width: 400px; height: 190px;">
                            <div class="jspPane" style="padding: 0px; width: 389px; top: 0px;">
                                <ul class="city-content J_provinces">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- page -->
        <!--        <div class="row-fluid" style="vertical-align: bottom" align="center">
                    <footer style="margin-top:0px;">
                        <hr>
                            <p>
                                Copyright &copy; 2014 by 北京爱吆喝信息技术有限公司 All Rights Reserved.<br/>
                            </p>
                    </footer>       
                </div>-->

        <!--        登陆Modal-->
        <div class="modal small hide fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div style="padding: 0px;max-height: 400px;margin-bottom: 10px;">
                <div class="row-fluid">
                    <div style="width:400px">
                        <div>
                            <p class="block-heading">登陆</p>
                            <div class="block-body">
                                <div class="login_row">
                                    <label>用户名</label>
                                    <div class="login_row_right">
                                        <input class="span12" id="username_txt" type="text" />                            
                                    </div>
                                </div>
                                <div class="login_row">
                                    <label class="login_row_left">密码</label>
                                    <div class="login_row_right">
                                        <input class="span12" id="pwd_txt" type="password" />                        
                                    </div>
                                </div>
                                <input type="button" class="btn btn-primary pull-right" onclick="login()" style="width: 50%;margin-top: 20px;" value="登陆"/>
                                <input type="button" class="btn btn-primary pull-right" onclick="register()" style="width: 50%;margin-top: 20px;" value="注册"/>
                                <div style="clear:both"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--        完善个人信息Modal-->
        <div class="modal small hide fade" id="finishUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h3 id="myModalLabel">完善个人信息</h3>
            </div>
            <form id="f_finishUser" method="post" action="#">
                <div class="modal-body">
                    <div class="well">
                        <div class="content_row">
                            <div class="row_left" style="width: 46px;">
                                <p>用户名</p>		
                            </div>
                            <div class="row_right">
                                <span style="padding-top: 5px;margin-left: 15px;font-weight: bold;" id="usernameModal"></span>
                            </div>
                        </div>
                        <div class="content_row">
                            <div class="row_left">
                                <p>密码</p>		
                            </div>
                            <div class="row_right">
                                <input id="passwordModal" type='text' name='pwd'/>
                            </div>
                        </div>
                        <div class="content_row">
                            <div class="row_left">
                                <p>昵称</p>		
                            </div>
                            <div class="row_right">
                                <input id="nickName" type='text' name='nickname'/>
                            </div>
                        </div>
                        <div class="content_row">
                            <div class="row_left">
                                <p>手机号</p>		
                            </div>
                            <div class="row_right">
                                <input id="telphone" type='text' name='phone'/>
                            </div>
                        </div>
                        <div class="content_row">
                            <div class="row_left">
                                <p>邮箱</p>		
                            </div>
                            <div class="row_right">
                                <input id="email" type='text' name='email'/>
                            </div>
                        </div>
                        <div class="content_row">
                            <div class="row_left">
                                <p>性别</p>		
                            </div>
                            <div class="row_right">
                                <input id="male" type='radio' name='sex' value="1" checked="true"/>男
                                <input id="female" type='radio' name='sex' value="0"/>女
                            </div>
                        </div>
                        <div class="content_row">
                            <div class="row_left">
                                <p>头像</p>		
                            </div>
                            <div class="row_right">
                                <img id="image_url_img" style="height: 50px;" ></img>
                                <input id="image_url" type='hidden' name='image_url'/>
                                <a href="javascript:showUploadFileModal('image_url',true)" style="float: right;margin-top: 5px;">上传</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">
                        取消
                    </button>
                    <input type='hidden' class='access_token' name='access_token' value='0'/>
                    <input type='hidden' name='os_type' value='5'/>
                    <input type='hidden' name='modeldes' value='web'/>
                    <input type="button" id="btnFinishUserSave" class="btn btn-primary pull-right" onclick="saveUserInfo()" value="保存"/>
                </div>
            </form>
        </div>

        <!--        我的好友Modal-->
        <div class="modal small hide fade" id="friendsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h3 id="myModalLabel">我的好友</h3>

            </div>

            <div class="modal-body">
                <div>
                    <input id="searchtxt" type='text' name='pwd' placeholder="输入好友名称"/>
                    <input type="button" id="btnSearch" onclick="findFriend()" class="btn btn-primary pull-right" value="查询"/>
                </div>
                <div id="friendsDiv" class="yaoheinfowell well">
                </div>
            </div>
        </div>

        <!--        我的消息Modal-->
        <div class="modal small hide fade" id="unreadMsgModal" tabindex="-1" role="dialog" style="width:620px;" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h3 id="myModalLabel">我的消息</h3>

            </div>

            <div class="modal-body">
                <div id="msgDiv" class="yaoheinfowell well">
                </div>
            </div>
        </div>

        <!--        上传文件Modal-->
        <div class="modal small hide fade" id="uploadFileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h3 id="myModalLabel">上传文件</h3>
            </div>
            <form id="f_login" action='#' method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="block-body">
                            <input type='hidden' class='access_token' name='access_token' value='0'/>
                            <input id="id_uploadfile" type="file" name="file" /> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">
                        取消
                    </button>
                    <input type="button" id="ajaxSubmit" class="btn btn-danger" onclick="uploadfile();" value="上传"/>
                </div>
            </form>
        </div>

        <!--        发表评论Modal-->
        <div class="modal small hide fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h3 id="myModalLabel">发表评论</h3>
            </div>
            <form id="f_comment" action='#' method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="block-body">
                            <input type="textarea" name="content" /> 
                            <input id="comment_yaoheID" type='hidden' name='yaoheID' value='0'/>
                            <input id="comment_commentedID" type='hidden' name='commentedID' value='0'/>
                            <input type='hidden' class="access_token" name='access_token' value='0'/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">
                        取消
                    </button>
                    <input type="button" id="ajaxSubmit" onclick="saveComment();" class="btn btn-danger" value="发表"/>
                </div>
            </form>
        </div>

        <!--        修改好友备注Modal-->
        <div class="modal small hide fade" id="updateFriendNameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h3 id="myModalLabel">修改好友备注</h3>
            </div>
            <form id="f_update_friend_name" action='#' method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="block-body">
                            <input id="friend_name" type="text" name="friendName" /> 
                            <input id="friend_userid" type='hidden' name='userid' value='0'/>
                            <input type='hidden' class="access_token" name='access_token' value='0'/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">
                        取消
                    </button>
                    <input type="button" id="ajaxSubmit" onclick="updateFriendName();" class="btn btn-danger" value="修改"/>
                </div>
            </form>
        </div>

        <!--        确认取消Modal-->
        <div class="modal small hide fade" id="deleteYaoheModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h3 id="myModalLabel">删除确认</h3>
            </div>
            <div class="modal-body">
                <p class="error-text">
                    <i class="icon-warning-sign modal-icon"></i>确认要删除发布的信息?
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">
                    取消
                </button>
                <button class="btn btn-danger" id="btnDeleteYaohe" data-dismiss="modal">
                    删除
                </button>
            </div>
        </div> 

        <!--        正在加载Modal-->
        <div class="modal small hide fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <img src="images/loading40.gif"/>
            <div style="margin:0px 10px;margin-top: -20px;">
                &nbsp;&nbsp;&nbsp;
                <span class="fname_msg"></span>&nbsp;&nbsp;&nbsp;
<!--                <span class="status"></span>-->
                <div class="progress">
                    <span class="bar"></span>&nbsp;
                    <span class="percent">0%</span>
                </div>
            </div>
        </div>

        <!--        问题反馈Modal-->
        <div class="modal small hide fade" id="questionsModal" style="width:620px;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h3 id="myModalLabel">问题反馈</h3>
            </div>

            <div class="modal-body">
                <div id="id_all_questions" class="yaoheinfowell well">
                    <table class="table" id="list_table">
                        <thead>
                            <tr height="31">
                                <th width="50">编号</th>
                                <th width="150">问题描述</th>
                                <th width="100">提交用户</th>
                                <th width="100">设备信息</th>
                                <th width="100">系统信息</th>
                                <th width="100">软件版本</th>
                                <th width="150">提交时间</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="css/lib/bootstrap/js/bootstrap.js"></script>
        <script src="css/webjs/index_web.js"></script>
        <script src="css/webjs/login.js"></script>
        <script src="css/webjs/friend.js"></script>
        <script src="css/webjs/gaodemap.js"></script>
        <script src="css/webjs/message.js"></script>
        <script src="css/webjs/yaohe.js"></script>
        <script src="css/webjs/city.js"></script>
        <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=437dc25e951aa5c1808972f272e11c35"></script>
    </body>
</html>
