var importModule = angular.module("ImportApp", ['ui.bootstrap', 'ngSanitize'], function ($httpProvider) {
    // Use x-www-form-urlencoded Content-Type
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    /**
     * The workhorse; converts an object to x-www-form-urlencoded serialization.
     * @param {Object} obj
     * @return {String}
     */
    var param = function (obj) {
        var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

        for (name in obj) {
            value = obj[name];

            if (value instanceof Array) {
                for (i = 0; i < value.length; ++i) {
                    subValue = value[i];
                    fullSubName = name + '[' + i + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if (value instanceof Object) {
                for (subName in value) {
                    subValue = value[subName];
                    fullSubName = name + '[' + subName + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if (value !== undefined && value !== null)
                query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
        }

        return query.length ? query.substr(0, query.length - 1) : query;
    };

    // Override $http service's default transformRequest
    $httpProvider.defaults.transformRequest = [function (data) {
            return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
        }];
});

importModule.factory("HTTPService", ["$http", function ($http) {
        var doRequest = function (method, url, data) {
            return $http({
                method: method,
                url: url,
                data: data,
//                headers: {
//                    'Content-Type': 'application/x-www-form-urlencoded'
//                }
            });
        };
        return {
            request: function (method, url, data) {
                return doRequest(method, url, data);
            }
        };
    }]);

importModule.controller("TableCtrl", ["$scope", "HTTPService", "$timeout",
    function ($scope, HTTPService, $timeout) {
        $scope.serviceUrl = "http://api.iyaohe.cc/";
        //$scope.serviceUrl = "http://local.api.justhere.cn/";
        $scope.opDisplay = false;

        $scope.max_count = 100;//每次抓取数据的数量
        $scope.webType = 1;//抓取网站类型。 1：花瓣网；2：Eput；3：按条件抓取花瓣；4：大众点评
        $scope.huaban_search = "";//抓取花瓣的搜索条件
        $scope.importType = 1;//导入类型。1：随机导入；2：指定位置导入   
        $scope.dataList = [];//抓到的数据
        $scope.selected_count = 0;//选择数据的数量
        $scope.is_selected_all = false;//全选按钮绑定字段
        $scope.data_config_div_show = false;//获取配置界面的显示标志
        $scope.data_import_div_show = false;//导入配置界面的显示标志
        $scope.markers = [];//地图上所有导入的标记
        $scope.selected_radius = 1000;//默认导入的半径
        $scope.selected_position = {};//选择位置model
        $scope.shopids;//大众点评商店id数组
        $scope.addressList = [];//地图查询地址数组


        var shopidlist = [];
        var shopid_index = 0;//大众点评商店id索引

        //
        //导入用户的id数组
        var user_arr = [11, 12, 13, 15, 44, 45, 46, 47, 48, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90];
        var max_huaban_id = 277441121222;//花瓣网的id
        var huaban_page = 1;//花瓣网的id
        var max_eput_id = 0;//eput网的id
        var count = 0;//已抓取的数据数量
        var getDataStatus = false;//获取数据状态
        var importDataCount = 0;//导入数据数量
        var infoWindow = null;//地图上的信息窗体
        var mapObj = null;//高德地图对象
        var mapObj_pos = null;//高德地图选位置对象
        var currentMarker = null;//位置地图标记
        var jcrop_api;//裁剪图片类库api对象
        var edit_image = {};//编辑图片model

        //抓取数据
        $scope.getDataList = function () {
            if (getDataStatus)
            {
                return;
            }
            if ($scope.webType.toString() == '4')
            {
                $scope.importType = 3;
                if ($scope.shopids == null || $scope.shopids == '')
                {
                    alert('请输入商店id,以逗号分隔');
                    return;
                }
                shopidlist = $scope.shopids.split(',');
                if (shopidlist.length == 0)
                {
                    alert('请输入商店id,以逗号分隔');
                    return;
                }
            }

            $scope.data_config_div_show = false;
            getDataStatus = true;
            window.localStorage.setItem("max_count", $scope.max_count);
            window.localStorage.setItem("webType", $scope.webType);
            getData();
        };

        $scope.cleanData = function () {
            $scope.dataList = [];
        };

        $scope.searchAddress = function () {
            $scope.addressList = [];
            geocoderLngLat($scope.selected_position.address, function (status, result) {
                if (status === 'complete' && result.info === 'OK') {
                    result.geocodes.forEach(function (item, index) {
                        $scope.addressList.push(
                                {
                                    lng: item.location.lng,
                                    lat: item.location.lat,
                                    address: item.formattedAddress
                                }
                        );
                    });
                }
                $scope.$apply();
            });
        };

        $scope.selectAddress = function (address) {
            //$scope.selected_position.index = index;
            $scope.selected_position.lng = address.lng;
            $scope.selected_position.lat = address.lat;
            $scope.selected_position.address = address.address;
            $scope.addressList = [];
            var pos = new AMap.LngLat($scope.selected_position.lng, $scope.selected_position.lat);
            if (currentMarker != null)
            {
                currentMarker.setMap(null);
                currentMarker = null;
            }
            currentMarker = new AMap.Marker({
                map: mapObj_pos,
                position: pos,
                //icon: "http://webapi.amap.com/images/0.png",
                icon: "http://api.iyaohe.cc/images/hongse.png",
                offset: new AMap.Pixel(-10, -34)
            });
            mapObj_pos.setCenter(pos);
        };

        var getData = function () {
//            var myModal = $('#loadingModal');
//            myModal.modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
//            myModal.modal('show');
            $scope.opDisplay = true;
            count = 0;
            shopid_index = 0;
            var url = getUrl();
            getFromWeb(url);
        };

        var getFromWeb = function (url)
        {
            var params = getParamsUrl();
            HTTPService.request("post", url, params)
                    .success(function (result, status) {

                        //var result = JSON.parse(data.data);
                        if (result.data && result.data.length > 0)
                        {
                            if ($scope.markers.length < 1)
                            {
                                alert("请先在地图上标记坐标！");
                                $scope.opDisplay = false;
                                count = 0;
                                getDataStatus = false;
                                return;
                            }
                            result.data.forEach(function (args) {
                                getRandomPostion(args);
                            });
                            count += result.data.length;
                            switch ($scope.webType.toString())
                            {
                                case "1":
                                    max_huaban_id = result.data[result.data.length - 1].id;
                                    break;
                                case "2":
                                    max_eput_id += $scope.dataList.length;
                                    break;
                                case "3":
                                    huaban_page++;
                                    break;
                                default:
                                    //max_huaban_id = result.data[result.data.length - 1].id;
                                    break;
                            }
                        }
                        else
                        {
                            //alert("没有抓取到数据！");
                            $scope.opDisplay = false;
                            count = 0;
                            getDataStatus = false;
                            return;
                        }
                        if ($scope.webType.toString() != "4" && ($scope.webType.toString() == "3" ? 20 : $scope.max_count) > count)
                        {
                            window.setTimeout(function () {
                                getFromWeb(url);
                            }, 10);
                        }
                        else if ($scope.webType.toString() == "4")
                        {
                            if (shopid_index >= shopidlist.length)
                            {
                                $scope.opDisplay = false;
                                getDataStatus = false;
                            }
                            else
                            {
                                window.setTimeout(function () {
                                    getFromWeb(url);
                                }, 100);
                            }
                        }
                    })
                    .error(function (data, status, headers, config) {
                        if ($scope.webType.toString() == "4")
                        {
                            if (shopid_index >= shopidlist.length)
                            {
                                $scope.opDisplay = false;
                                getDataStatus = false;
                            }
                            else
                            {
                                window.setTimeout(function () {
                                    getFromWeb(url);
                                }, 100);
                            }
                        }
                        else
                        {
                            alert("抓取出错！");
                            $scope.opDisplay = false;
                            count = 0;
                            getDataStatus = false;
                            return;
                        }
                    });
        };

        var getRandomPostion = function (item) {
            switch ($scope.importType.toString())
            {
                case "1":
                    getRandomPostionFromWeb(item);
                    break;
                case "2":
                    var randomIndex = Math.floor(Math.random() * $scope.markers.length);
                    item.lng = getRandomNumber($scope.markers[randomIndex].position.lng, $scope.markers[randomIndex].position.radius);
                    item.lat = getRandomNumber($scope.markers[randomIndex].position.lat, $scope.markers[randomIndex].position.radius);
                    geocoder(new AMap.LngLat(item.lng, item.lat), function (data) {
                        $scope.dataList.push(
                                {
                                    img: $scope.serviceUrl + "upload/temp/" + item.filename,
                                    text: item.title,
                                    is_selected: false,
                                    status: "",
                                    file: item.filename,
                                    file_src: item.filename_src,
                                    lng: item.lng,
                                    lat: item.lat,
                                    radius: $scope.selected_radius,
                                    address: data.regeocode.formattedAddress
                                }
                        );
                        if (count >= ($scope.webType.toString() == "3" ? 20 : $scope.max_count))
                        {
                            $scope.opDisplay = false;
                            count = 0;
                            getDataStatus = false;
                        }
                        $scope.$apply();
                    });
                    break;
                case "3":
                    geocoderLngLat(item.shop_name, function (status, result) {
                        if (status === 'complete' && result.info === 'OK') {
                            $scope.dataList.push(
                                    {
                                        img: $scope.serviceUrl + "upload/temp/" + item.filename,
                                        text: item.title,
                                        is_selected: false,
                                        status: "",
                                        file: item.filename,
                                        file_src: item.filename_src,
                                        lng: result.geocodes[0].location.lng,
                                        lat: result.geocodes[0].location.lat,
                                        radius: $scope.selected_radius,
                                        address: item.shop_name
                                    }
                            );

                        }
                        else
                        {
                            $scope.dataList.push(
                                    {
                                        img: $scope.serviceUrl + "upload/temp/" + item.filename,
                                        text: item.title,
                                        is_selected: false,
                                        status: "",
                                        file: item.filename,
                                        file_src: item.filename_src,
                                        lng: item.lng,
                                        lat: item.lat,
                                        radius: $scope.selected_radius,
                                        address: item.shop_name
                                    }
                            );
                        }
                        $scope.$apply();
                    });
                    break;
                default:
                    getRandomPostionWeb(item);
                    break;
            }
        };

        $scope.importData = function () {
            if (importDataCount > 0)
            {
                alert("正在导入中...");
                return;
            }
            $scope.data_import_div_show = false;
            window.localStorage.setItem("importType", $scope.importType);
            $scope.dataList.forEach(function (item) {
                if (item.is_selected)
                {
                    importDataCount++;
                    item.status = "正在导入...";
                    insertFile(item);
                }
            });
        };

        $scope.showGetShopid = function ()
        {
            $('#getShopModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
            $('#getShopModal').modal('show');

        };
        $scope.hideGetShopid = function ()
        {
            $('#getShopModal').modal('hide');
        };

        $scope.showPostion = function (index)
        {
            $('#selectPosModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
            $('#selectPosModal').modal('show');
            $scope.selected_position.index = index;
            $scope.selected_position.lng = $scope.dataList[index].lng;
            $scope.selected_position.lat = $scope.dataList[index].lat;
            $scope.selected_position.address = $scope.dataList[index].address;
            var pos = new AMap.LngLat($scope.selected_position.lng, $scope.selected_position.lat);
            if (currentMarker != null)
            {
                currentMarker.setMap(null);
                currentMarker = null;
            }
            currentMarker = new AMap.Marker({
                map: mapObj_pos,
                position: pos,
                //icon: "http://webapi.amap.com/images/0.png",
                icon: "http://api.iyaohe.cc/images/hongse.png",
                offset: new AMap.Pixel(-10, -34)
            });
            mapObj_pos.setCenter(pos);

        };
        $scope.hidePostion = function ()
        {
            if (currentMarker != null)
            {
                currentMarker.setMap(null);
                currentMarker = null;
            }
            $('#selectPosModal').modal('hide');
        };
        $scope.OKPosition = function ()
        {
            $scope.dataList[$scope.selected_position.index].lng = $scope.selected_position.lng;
            $scope.dataList[$scope.selected_position.index].lat = $scope.selected_position.lat;
            $scope.dataList[$scope.selected_position.index].address = $scope.selected_position.address;
            $('#selectPosModal').modal('hide');
        };

        $scope.showImgEdit = function (index) {
            $('#editImgModal').modal({backdrop: 'static', keyboard: false});//点击周围黑色，不会关闭dialog;按ESC键，不会关闭dialog
            $('#editImgModal').modal('show');
            edit_image.old_file = $scope.serviceUrl + "upload/temp/" + $scope.dataList[index].file_src;
            edit_image.old_file_name = $scope.dataList[index].file_src;
            edit_image.index = index;

            if (typeof jcrop_api != 'undefined')
            {
                jcrop_api.destroy();
            }
            $('#id_cut_img')[0].src = edit_image.old_file;
            $('#id_cut_img')[0].onload = function () {
//                alert(1);
//            };
//            $timeout(function() {
                // Create variables (in this scope) to hold the Jcrop API and image size
                //var boundx, boundy;
                var updateInfo = function (e)
                {
                    edit_image.x = e.x;
                    edit_image.y = e.y;
                    edit_image.x2 = e.x2;
                    edit_image.y2 = e.y2;
                    edit_image.w = e.w;
                    edit_image.h = e.h;
                };
                var clearInfo = function () {

                };
                // initialize Jcrop
                $('#id_cut_img').Jcrop({
                    minSize: [40, 30], // min crop size
                    aspectRatio: 1.333, // keep aspect ratio 1:1
                    bgFade: true, // use fade effect
                    bgOpacity: .3, // fade opacity
                    onChange: updateInfo,
                    onSelect: updateInfo,
                    onRelease: clearInfo
                }, function () {
                    // use the Jcrop API to get the real image size
//                    var bounds = this.getBounds();
//                    boundx = bounds[0];
//                    boundy = bounds[1];
                    // Store the Jcrop API in the jcrop_api variable
                    jcrop_api = this;
                });
            };
        };
        $scope.hideImgEdit = function () {
            edit_image = {};
            $('#editImgModal').modal('hide');
        };

        $scope.OKImgEdit = function () {
            if (!edit_image.w)
            {
                alert("请鼠标圈选需要截图的部位");
                return;
            }
            var url = $scope.serviceUrl + "tool/cutImage";
            //var json_data = JSON.stringify(edit_image);
            HTTPService.request("post", url, edit_image)
                    .success(function (result, status) {
                        if (result.code == 200)
                        {
                            $scope.dataList[edit_image.index].file = result.data;
                            $scope.dataList[edit_image.index].img = $scope.serviceUrl + "upload/temp/" + result.data;
                            $scope.hideImgEdit();
                        }
                        else
                        {
                            alert(result.message);
                        }
                    })
                    .error(function (data, status, headers, config) {
                        alert("服务器保存失败！");
                    });
        };

        var insertFile = function (item) {
            var url = $scope.serviceUrl + "tool/insertFile";

            HTTPService.request("post", url, {file_name: item.file})
                    .success(function (data, status) {
                        if (data.data)
                        {
                            publishYaohe(item, data.data);
                        }
                    })
                    .error(function (data, status, headers, config) {
                        item.status = "导入失败(保存文件失败)";
                        importDataCount--;
                    });
        };

        var getRandomPostionFromWeb = function (item)
        {
            var url = $scope.serviceUrl + "tool/getRandomPostion";
            HTTPService.request("post", url, {})
                    .success(function (data, status) {
                        if (data.data)
                        {
                            item.lng = data.data.lng;
                            item.lat = data.data.lat;
                            geocoder(new AMap.LngLat(item.lng, item.lat), function (data) {
                                $scope.dataList.push(
                                        {
                                            img: $scope.serviceUrl + "upload/temp/" + item.filename,
                                            text: item.title,
                                            is_selected: false,
                                            status: "",
                                            file: item.filename,
                                            file_src: item.filename_src,
                                            lng: item.lng,
                                            lat: item.lat,
                                            radius: $scope.selected_radius,
                                            address: data.regeocode.formattedAddress
                                        }
                                );
                                if (count >= ($scope.webType.toString() == "3" ? 20 : $scope.max_count))
                                {
                                    $scope.opDisplay = false;
                                    count = 0;
                                    getDataStatus = false;
                                }
                                $scope.$apply();
                            });
                        }
                    })
                    .error(function (data, status, headers, config) {
                        item.status = "导入失败(获取随机位置失败)";
                        importDataCount--;
                    });
        };

        var publishYaohe = function (item, file_id)
        {
            var url = $scope.serviceUrl + "app/publishYaohe";
            var userid = user_arr[Math.floor(Math.random() * 49)];
            var json_data = {context: item.text, longitude: item.lng, latitude: item.lat,
                address: item.address, radius: 150, file_id: file_id, access_token: userid};
            HTTPService.request("post", url, json_data)
                    .success(function (data, status) {
                        if (data.code == 200)
                        {
                            item.is_selected = false;
                            $scope.selected_count--;
                            item.status = "导入成功！";
                        }
                        else
                        {
                            item.status = "导入失败(" + data.message + ")";
                        }
                        importDataCount--;
                    })
                    .error(function (data, status, headers, config) {
                        item.status = "导入失败(发布信息失败)";
                        importDataCount--;
                    });
        };

        var initMap = function () {
            var position = new AMap.LngLat(116.397428, 39.90923);//天安门坐标
            mapObj = new AMap.Map("container", {
                view: new AMap.View2D({
                    center: position,
                    zoom: 11,
                    rotation: 0
                }),
                lang: "zh_cn"//设置语言类型，中文简体
            });


            mapObj_pos = new AMap.Map("pos_container", {
                view: new AMap.View2D({
                    center: position,
                    zoom: 11,
                    rotation: 0
                }),
                lang: "zh_cn"//设置语言类型，中文简体
            });
            window.mapObj = mapObj_pos;

            var marker_local = window.localStorage.getItem("markers");
            if (marker_local && marker_local != "undefined" && marker_local != "")
            {
                var markers_local_json = JSON.parse(marker_local);
                markers_local_json.forEach(function (item) {
                    var marker = new AMap.Marker({
                        map: mapObj,
                        position: new AMap.LngLat(item.lng, item.lat),
                        //icon: "http://webapi.amap.com/images/0.png",
                        icon: "http://api.iyaohe.cc/images/hongse.png",
                        offset: new AMap.Pixel(-10, -34)
                    });
                    marker.position = item;
                    $scope.markers.push(marker);
                    AMap.event.addListener(marker, "click", function (e) {
                        openInfoWindow(marker);
                    });
                });
            }
        };

        var addClickListener = function () {
            AMap.event.addListener(mapObj, "click", function (e) {
                var marker = new AMap.Marker({
                    map: mapObj,
                    position: e.lnglat,
                    //icon: "http://webapi.amap.com/images/0.png",
                    icon: "http://api.iyaohe.cc/images/hongse.png",
                    offset: new AMap.Pixel(-10, -34)
                });
                marker.position = {};
                marker.position.lng = e.lnglat.lng;
                marker.position.lat = e.lnglat.lat;
                marker.position.radius = $scope.selected_radius;
                $scope.markers.push(marker);

                geocoder(e.lnglat, function (data) {
                    //返回地址描述
                    $scope.markers[$scope.markers.length - 1].position.desc = data.regeocode.formattedAddress;
                    openInfoWindow($scope.markers[$scope.markers.length - 1]);
                    setLocalMarkers();
                });
                AMap.event.addListener(marker, "click", function (e) {
                    openInfoWindow(marker);
                });
            });

            AMap.event.addListener(mapObj_pos, "click", function (e) {
                if (currentMarker != null)
                {
                    currentMarker.setMap(null);
                    currentMarker = null;
                }

                currentMarker = new AMap.Marker({
                    map: mapObj_pos,
                    position: e.lnglat,
                    //icon: "http://webapi.amap.com/images/0.png",
                    icon: "http://api.iyaohe.cc/images/hongse.png",
                    offset: new AMap.Pixel(-10, -34)
                });
                geocoder(e.lnglat, function (data) {
                    $scope.selected_position.lng = e.lnglat.lng;
                    $scope.selected_position.lat = e.lnglat.lat;
                    //返回地址描述
                    $scope.selected_position.address = data.regeocode.formattedAddress;
                    $scope.$apply();
                });
            });
        };

        var geocoder = function (lnglatXY, callback) {
            var MGeocoder;
            //加载地理编码插件
            mapObj.plugin(["AMap.Geocoder"], function () {
                MGeocoder = new AMap.Geocoder({
                    radius: 1000,
                    extensions: "all"
                });
                //返回地理编码结果 
                AMap.event.addListener(MGeocoder, "complete", callback);
                //逆地理编码
                MGeocoder.getAddress(lnglatXY);
            });
        };

        var geocoderLngLat = function (address, callback) {

            var geocoderLngLat; 
//加载地理编码服务
            AMap.service(["AMap.Geocoder"], function () {       
                    geocoderLngLat = new AMap.Geocoder({
                            //city: "010", //城市，默认：“全国”
                            radius: 1000 //范围，默认：500
                    }); 
                    //地理编码
                    geocoderLngLat.getLocation(address, callback);
            });

        };

        var setLocalMarkers = function () {
            var positions = [];
            $scope.markers.forEach(function (item) {
                positions.push(item.position);
            });
            window.localStorage.setItem("markers", JSON.stringify(positions));
        };

        var openInfoWindow = function (marker)
        {
            var info = [];
            info.push("经度：" + marker.position.lng);
            info.push("纬度：" + marker.position.lat);
            info.push("地址：" + marker.position.desc);
            info.push("半径：" + marker.position.radius);
            infoWindow = new AMap.InfoWindow({
                autoMove: true, //是否自动调整信息窗体至视野内
                isCustom: true, //使用自定义窗体
                offset: new AMap.Pixel(16, -45),
                content: createInfoWindow(info.join("<br>"), marker)
            });
            infoWindow.open(mapObj, marker.getPosition());
        };

        //构建自定义信息窗体	
        var createInfoWindow = function (content, marker) {
            var info = document.createElement("div");
            info.className = "info";

            //可以通过下面的方式修改自定义窗体的宽高
            info.style.width = "350px";

            // 定义顶部标题
            var top = document.createElement("div");
            top.className = "info-top";
            var titleD = document.createElement("div");
            titleD.innerHTML = '恰好';

            var btnDel = document.createElement("button");
            btnDel.className = "btn btn-primary";
            btnDel.innerHTML = "删除标记";
            btnDel.style.width = "60px;";
            btnDel.style.marginLeft = "10px;";
            btnDel.style.padding = "0";
            btnDel.onclick = function (e) {
                deleteMarker(e, marker);
            };
            //btnDel.innerHTML = '<button type="button" class="btn btn-primary" style="width: 60px;margin:left:10px;">删除</button>';

            var closeX = document.createElement("img");
            closeX.src = "http://webapi.amap.com/images/close2.gif";
            closeX.onclick = closeInfoWindow;

            top.appendChild(titleD);
            top.appendChild(btnDel);
            top.appendChild(closeX);
            info.appendChild(top);


            // 定义中部内容
            var middle = document.createElement("div");
            middle.className = "info-middle";
            middle.style.backgroundColor = 'white';
            middle.innerHTML = content;
            info.appendChild(middle);

            // 定义底部内容
            var bottom = document.createElement("div");
            bottom.className = "info-bottom";
            bottom.style.position = 'relative';
            bottom.style.top = '0px';
            bottom.style.margin = '0 auto';
            var sharp = document.createElement("img");
            sharp.src = "http://webapi.amap.com/images/sharp.png";
            bottom.appendChild(sharp);
            info.appendChild(bottom);
            return info;
        };

        var deleteMarker = function (e, m)
        {
            if (m != null)
            {
                m.setMap(null);
                $scope.markers.splice($.inArray(m, $scope.markers), 1);
                setLocalMarkers();
            }
            closeInfoWindow();
        };

//关闭信息窗体
        var closeInfoWindow = function () {
            mapObj.clearInfoWindow();
        };

        var getRandomNumber = function (number, radius)
        {
            var random = Math.floor(Math.random() * radius);
            random = random / 100000;
            if (Math.random() > 0.5)
            {
                number = number + random;
            }
            else
            {
                number = number - random;
            }

            return number;
        };

//获取请求接口的url
        var getUrl = function () {
            var url = $scope.serviceUrl;
            switch ($scope.webType.toString())
            {
                case "1":
                    url += "tool/getdatafromhuaban";
                    break;
                case "2":
                    url += "tool/getdatafromeput";
                    break;
                case "3":
                    //url += "tool/getdatafromhuaban";
                    url += "tool/getdatafromhuabanbysearch";
                    break;
                case "4":
                    //url += "tool/getdatafromhuaban";
                    url += "tool/getdatafromauto";
                    break;
                default:
                    url += "tool/getdatafromhuaban";
                    break;
            }
            return url;
        };

        //获取抓取网站的url
        var getParamsUrl = function () {
            var url = null;
            switch ($scope.webType.toString())
            {
                case "1":
                    url = {url: "http://huaban.com/?i2prvbxi&max=" + max_huaban_id + "&limit=1&wfl=1"};
                    break;
                case "2":
                    url = {url: "http://eput.com/api/blockps/dynamic?limit=" + $scope.max_count + "&excur=" + max_eput_id};
                    break;
                case "3":
                    //url = {url: "http://huaban.com/search/?q=" + encodeURI($scope.huaban_search) + "&page=" + huaban_page + "&per_page=1&wfl=1"};
                    //url = {url:"http://huaban.com/search/?q="+$scope.huaban_search+"&qq-pf-to=pcqq.group"};
                    url = {q: $scope.huaban_search, page: huaban_page};
                    break;
                case '4':
                    //url = {shopid: '19645599'};
                    url = {shopid: shopidlist[shopid_index]};
                    shopid_index++;
                    break;
                default:
                    url = {url: "http://huaban.com/?i2prvbxi&max=" + max_huaban_id + "&limit=1&wfl=1"};
                    break;
            }
            return url;
        };

        //从本地存储中获取历史配置
        var getConfigFromLocal = function () {
            if (window.localStorage.getItem("max_count"))
            {
                $scope.max_count = parseInt(window.localStorage.getItem("max_count"));
            }
            if (window.localStorage.getItem("webType"))
            {
                $scope.webType = parseInt(window.localStorage.getItem("webType"));
            }
            if (window.localStorage.getItem("importType"))
            {
                $scope.importType = parseInt(window.localStorage.getItem("importType"));
            }
        };

        //获取数据配置div
        $scope.data_config_div_click = function ()
        {
            $scope.data_config_div_show = !$scope.data_config_div_show;
            if ($scope.data_config_div_show)
                $scope.data_import_div_show = false;
        };

        //导入数据配置div
        $scope.data_import_div_click = function ()
        {
            $scope.data_import_div_show = !$scope.data_import_div_show;
            if ($scope.data_import_div_show)
                $scope.data_config_div_show = false;
        };

        //全选checkbox按钮的click事件
        $scope.cb_all_click = function () {
            $scope.is_selected_all = !$scope.is_selected_all;
            $scope.dataList.forEach(function (item) {
                item.is_selected = $scope.is_selected_all;
            });
            if ($scope.is_selected_all)
                $scope.selected_count = $scope.dataList.length;
            else
                $scope.selected_count = 0;
        };

        //数据checkbox的click事件
        $scope.cb_click = function (index)
        {
            $scope.dataList[index].is_selected = !$scope.dataList[index].is_selected;
            if ($scope.dataList[index].is_selected)
            {
                $scope.selected_count++;
            }
            else
            {
                $scope.selected_count--;
                $scope.is_selected_all = false;
            }
        };

        $scope.cleanMark = function ()
        {
            $scope.markers.forEach(function (item) {
                item.setMap(null);
                item = null;
            });
            $scope.markers = [];
            window.localStorage.setItem("markers", "");
        };

        getConfigFromLocal();
        initMap();
        addClickListener();
        //初始化城市数据
        init_city_data(i);
    }]);

importModule.controller("DazhongCityCtrl", ["$scope", "$sce", "HTTPService",
    function ($scope, $sce, HTTPService) {
        var base_url = "http://api.iyaohe.cc/";
        //var base_url = "http://local.api.justhere.cn/";
        $scope.input_cityid;//输入框shopid
        $scope.input_cityname;//输入框城市名称
        $scope.shopid_result;//查询城市shopid结果
        $scope.search_ing = false;
        $scope.filename = '';
        $scope.downloadtxt = '';
//        $scope.$watch('input_cityid', function () {
//            if (isNaN($scope.input_cityid) || $scope.input_cityid == null || $scope.input_cityid == '')
//            {
//                $scope.search_ing = false;
//                $scope.input_cityname = '';
//                return;
//            }
//            $scope.search_ing = true;
//            var url = base_url + 'tool/GetCityNameByid';
//            HTTPService.request("post", url, {cityid: $scope.input_cityid})
//                    .success(function (result, status) {
//                        if (result.code == 200 && result.data)
//                        {
//                            $scope.input_cityname = result.data.city_name;
//                            //$scope.$apply();
//                        }
//                        else
//                        {
//
//                        }
//                        $scope.search_ing = false;
//                    })
//                    .error(function (data, status, headers, config) {
//                        $scope.search_ing = false;
//                        alert("GetCityNameByid error");
//                    });
//
//        });

        $scope.getShopid = function () {
//            if (isNaN($scope.input_cityid) || $scope.input_cityid == null || $scope.input_cityid == '')
//            {
//                $scope.search_ing = false;
//                alert('请输入城市id');
//                return;
//            }

            $scope.search_ing = true;
            var url = base_url + 'tool/GetShopidByCity';
            HTTPService.request("post", url,
                    {cityid: $scope.input_cityid, page_offset: $scope.input_page_offset, page_count: $scope.input_page_count})
                    .success(function (result, status) {
                        if (result.code == 200 && result.data)
                        {
                            $scope.shopid_result = $sce.trustAsHtml(result.data);
                            $scope.filename = result.filename;
                            $scope.downloadtxt = 'upload/shoptxt/' + $scope.filename;
                        }
                        else
                        {
                            alert(result.message);
                        }
                        $scope.search_ing = false;
                    })
                    .error(function (data, status, headers, config) {
                        $scope.search_ing = false;
                        alert("GetShopidByCity error");
                    });
        };
    }]);

