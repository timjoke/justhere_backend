<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html ng-app="ImportApp">
    <head>
        <meta charset="UTF-8">
        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="css/ie.css" media="screen, projection" />
        <![endif]-->
        <script src="css/lib/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="css/lib/jquery.ui.js" type="text/javascript"></script>
        <script src="css/lib/jquery.form.js" type="text/javascript"></script>
        <script src="css/lib/jquery.Jcrop.min.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/lib/bootstrap-3.0.0/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/importcss.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.Jcrop.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/mapinfowindow.css" />
        <link rel="stylesheet" href="css/webcss.css"/>
        <script src="css/lib/bootstrap-3.0.0/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="css/lib/1.3.0.14/angular.js" type="text/javascript"></script>
        <script src="css/lib/1.3.0.14/angular-sanitize.js" type="text/javascript"></script>
        <script src="css/lib/angular-ui-bootstrap/ui-bootstrap-tpls.js" type="text/javascript"></script>
        <script src="js/import_app.js" type="text/javascript"></script>
        <script src="css/webjs/city.js"></script>
        <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=437dc25e951aa5c1808972f272e11c35"></script>
        <title>恰好导入数据</title>

    </head>

    <body ng-controller="TableCtrl" style="overflow:hidden;">
        <div style="top:0;left:0;height:100%;width:100%;background-color:gray;
             color:white;position:absolute;z-index:99;">
            <p>网站维护中...</p>
        </div>

        <!--        Header部分-->
        <div class="header navbar">
            <div ng-show="opDisplay" class="web-top-status">正在加载...</div>
            <ul class="pull-right" style="margin-right: 20px;">
                <li ng-click="cleanData()">
                    <a style="width: 100px;">清空</a>
                </li>
                <li ng-click="showGetShopid()">
                    <a style="width: 100px;">获取大众点评店铺id</a>
                </li>
                <li ng-click="data_import_div_click()" ng-class="{'li_selected':data_import_div_show}">
                    <a style="width: 100px;">本地位置设置</a>
                </li>
                <li ng-click="data_config_div_click()" ng-class="{'li_selected':data_config_div_show}">
                    <a style="width: 60px;">抓取设置</a>
                </li>
            </ul>
            <div>
                <span>恰好导入数据</span>
                <span class="text-red-font">
                    &nbsp;&nbsp;&nbsp;&nbsp;共({{dataList.length}})个，已选择({{selected_count}})个
                </span>
                <button type="button" class="btn btn-primary" ng-click="importData()" style="width: 60px;">导入</button>
            </div>
        </div>
        <!--        获取数据设置-->
        <div class="drop-data-config pull-right" ng-show="data_config_div_show">
            <table class="table table-condensed">
                <tr>
                    <td>
                        <label class="radio-inline">
                            <input type="radio" name="webTypeRadioOptions" value="1" ng-model="webType"> 花瓣网
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="webTypeRadioOptions" value="2" ng-model="webType"> Eput
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="webTypeRadioOptions" value="3" ng-model="webType"> 花瓣-搜索
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="webTypeRadioOptions" value="4" ng-model="webType"> 大众点评
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="radio-inline">
                            <input type="radio" name="importTypeRadioOptions" value="1" ng-model="importType"> 随机位置
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="importTypeRadioOptions" value="2" ng-model="importType"> 指定本地缓存位置周边
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="importTypeRadioOptions" value="3" ng-model="importType"> 大众点评获取
                        </label>
                    </td>
                </tr>
                <tr ng-show="webType == 1 || webType == 2 || webType == 3">
                    <td>
                        半径：<input type="number" ng-model="selected_radius"/>
                    </td>
                </tr>
                <tr ng-show="webType == 1 || webType == 2 || webType == 3">
                    <td>获取数量：<input type="number" ng-model="max_count"/></td>
                </tr>
                <tr ng-show="webType == 3">
                    <td>查询关键字：<input type="text" ng-model="huaban_search"/></td>
                </tr>
                <tr ng-show="webType == 4">
                    <td>商铺id：<textarea style="width:80%;height:200px;" ng-model="shopids"></textarea></td>
                </tr>
                <tr>
                    <td><button type="button" ng-click="getDataList()" class="btn btn-primary" style="width: 60px;">抓取</button></td>
                </tr>
            </table>
        </div>

        <!--        导入设置-->
        <div class="drop-import-config pull-right" 
             ng-show="data_import_div_show">
            <button type="button" ng-click="cleanMark()" class="btn btn-primary" style="width: 60px;padding:0;float: right;margin-right:10px;">清空位置</button>
            <div id="container"></div>
        </div>

        <!--        表格部分-->
        <div class="container" style="margin-top:50px;width:98%;">
            <div class="row">
                <div class="well" style="border: 1px solid #ccc;">
                    <table class="table" style="text-align:center;">
                        <thead>
                            <tr>
                                <th width="80"><input type="checkbox" ng-model="is_selected_all" ng-click="cb_all_click()"/>全选</th>
                                <th width="80">编号</th>
                                <th width="200">描述</th>
                                <th width="100">图片</th>
                                <th width="100">经纬度</th>
                                <th width="150">地址</th>
                                <th width="100">状态</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in dataList">
                                <td><input type="checkbox" ng-model="item.is_selected" ng-click="cb_click($index)"/></td>
                                <td>{{$index + 1}}</td>
                                <td><textarea ng-model="item.text" style="width:300px;height:100px;"></textarea></td>
                                <td><image src="{{item.img}}" style="cursor:pointer;" ng-click="showImgEdit($index)"></td>
                                <td>
                                    <div>
                                        经度：
                                        <a ng-click="showPostion($index)" style="float:right;cursor:pointer;margin-right: 35px;">选择位置</a>
                                        <input type="text" ng-model="item.lng"/></div>
                                    <div>纬度：<br><input type="text" ng-model="item.lat"/></div>
                                </td>
                                <td><textarea ng-model="item.address" style="width:200px;height:100px;"></textarea></td>
                                <td><span class="text-warning">{{item.status}}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--        正在加载Modal-->
        <div id="loadingModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <img src="images/loading40.gif"/>
            </div>
        </div>

        <!--        地图选点Modal-->
        <div class="modal fade" id="selectPosModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <img src="images/close.jpg" ng-click="hidePostion()" data-dismiss="modal" style="height:30px;position:absolute;right:0;
                         margin-right: -15px;margin-top:-15px;margin-bottom:-15px;z-index:20;cursor:pointer;">
                    <!--                    <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="myModalLabel">选择位置</h4>
                                        </div>-->
                    <div class="modal-body" style="height:500px;padding:0;">
                        <!--        切换城市-->
                        <div id="city-box" class="city-list-item J_cityContainer" style="position: absolute;
                             z-index: 9;
                             background-color: white;
                             width: 35%;
                             display:none;
                             top: 7%;">
                            <div class="city-list-item city-list-title" style="height:40px;">
                                <span>选择城市</span>
                                <a class="city-list-close-btn"></a>
                            </div>
                            <div class="city-list-item city-list-hot J_hotCities">

                            </div>
                            <div class="city-list-item city-list-srh">
                                <input type="text" style="height:auto;" class="city-list-ipt city-srh-ipt J_citySearchIpt ui-autocomplete-input" id="city-srh-ipt"
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
                        <!--                        <div style="z-index: 10;
                                                     width: 60%;
                                                     left: 20%;
                                                     margin: 0 auto;
                                                     position: absolute;">
                                                    <label style="width:100%;">
                                                        <input type="text" ng-model="vm.selected" typeahead="state for state in vm.states | filter:$viewValue | limitTo:8"
                                                               class="form-control" placeholder="请输入地址">
                                                    </label>
                                                </div>-->
                        <div id="pos_container"></div>
                        <a href="javascript:showOrHideCitySelect();" role="button" class="dropdown-toggle" 
                           style="position: absolute;
                           top: 10px;
                           background-color: azure;
                           padding: 5px;">
                            <span id="id_current_city">北京市</span>
                            <i class="icon-caret-down"></i>
                        </a>
                    </div>
                    <div class="modal-footer" style="padding: 10px 10px 10px;margin-top:0;">
                        <div style="display: inline;float: left;">
                            经度：<input type="text" ng-model="selected_position.lng" />
                            纬度：<input type="text" ng-model="selected_position.lat" />
                            地址：<input type="text" ng-model="selected_position.address" style="width:300px;"/>
                        </div>
                        <button type="button" ng-click="searchAddress()" class="btn btn-default">查询</button>
                        <button type="button" ng-click="hidePostion()" class="btn btn-default">取消</button>
                        <button type="button" class="btn btn-primary" ng-click="OKPosition()">确定</button>
                    </div>
                    <div ng-show="addressList.length > 0" style="position: absolute;
                         bottom: 8%;
                         background-color: white;
                         z-index: 1110;
                         left: 35%;
                         box-shadow: 0px 0px 3px #cccccc;">
                        <ul>
                            <li class="li-address" style="padding:2px;cursor:pointer;" ng-repeat="item in addressList">
                                <a ng-click="selectAddress(item)" href="#">{{item.address}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!--        图片裁剪编辑Modal-->
        <div class="modal fade" id="editImgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width:90%;">
                <div class="modal-content">
                    <img src="images/close.jpg" ng-click="hideImgEdit()" data-dismiss="modal" style="height:30px;position:absolute;right:0;
                         margin-right: -15px;margin-top:-15px;margin-bottom:-15px;z-index:20;cursor:pointer;">
                    <div class="modal-body" style="background: url(images/bg.png)">
                        <img id="id_cut_img" style="margin: 0 auto;">
                    </div>
                    <div class="modal-footer" style="padding: 10px 10px 10px;margin-top:0;">
                        <button type="button" ng-click="hideImgEdit()" class="btn btn-default">取消</button>
                        <button type="button" class="btn btn-primary" ng-click="OKImgEdit()">确定</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        抓取商铺idModal-->
        <div class="modal fade" id="getShopModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <img src="images/close.jpg" ng-click="hideGetShopid()" data-dismiss="modal" style="height:30px;position:absolute;right:0;
                         margin-right: -15px;margin-top:-15px;margin-bottom:-15px;z-index:20;cursor:pointer;">
                    <div class="modal-body" style="height:500px;padding:0;text-align: center;" ng-controller="DazhongCityCtrl">
                        <div style="margin-top: 3%;">
                            <span>{{input_cityname}}</span>                           
                            <input type="text" ng-model="input_page_offset" placeholder="请输入起始页码" style="width:10%;"/>
                            <input type="text" ng-model="input_page_count" placeholder="请输入抓取页数" style="width:10%;"/>
                            <input type="text" ng-model="input_cityid" placeholder="请输入url" style="width:40%;"/>
                            <button type="button" ng-disabled="search_ing" class="btn btn-primary" ng-click="getShopid()" style="width: 100px;">获取</button>
                            <span ng-show="search_ing">正在拼命搜索大众点评...请稍等！</span>
                            <a href="download.php?file={{downloadtxt}}">下载{{filename}}</a>
                        </div>
                        <div style="margin-top: 2%;height:88%;overflow-y:auto;" ng-bind-html="shopid_result">
                           
<!--                            <textarea ng-model="shopid_result" style="width:80%;height:80%;">{{shopid_result}}</textarea>-->
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 10px 10px 10px;margin-top:0;">

                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
