//初始化高德地图
function init_amap()
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

//    //设置地图上默认鼠标样式
//    mapObj.setDefaultCursor("url(http://developer.amap.com/wp-content/uploads/2014/06/openhand.cur),pointer");
//
//    //通过地图的dragstart、dragging、dragend事件切换鼠标拖拽地图过程中的不同样式
//    AMap.event.addListener(mapObj, 'dragstart', function(e) {
//        mapObj.setDefaultCursor("url(http://developer.amap.com/wp-content/uploads/2014/06/closedhand.cur),pointer");
//    });
//    AMap.event.addListener(mapObj, 'dragging', function(e) {
//        mapObj.setDefaultCursor("url(http://developer.amap.com/wp-content/uploads/2014/06/closedhand.cur),pointer");
//    });
//    AMap.event.addListener(mapObj, 'dragend', function(e) {
//        mapObj.setDefaultCursor("url(http://developer.amap.com/wp-content/uploads/2014/06/openhand.cur),pointer");
//        var mapCenter = mapObj.getCenter();
//        init_yaohe(mapCenter);
//    });

    //叠加3D楼块图层
    if (typeof (Worker) !== "undefined") {
        var buildings = new AMap.Buildings(); //实例化3D楼块图层
        buildings.setMap(mapObj);//在map中添加3D楼块图层
    }

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
    
    var listener = AMap.event.addListener(mapObj, "click", function(e) {
        //mapObj.setZoom(11);

        if (currentMarker != null)
        {
            currentMarker.setMap(null);
            currentMarker = null;
        }
        currentMarker = new AMap.Marker({
            map: mapObj,
            position: e.lnglat,
            //icon: "http://webapi.amap.com/images/0.png",
            icon: "http://api.iyaohe.cc/images/hongse.png",
            offset: new AMap.Pixel(-10, -34)
        });
        //currentMarker.setAnimation('AMAP_ANIMATION_BOUNCE');
        geocoder(e.lnglat);
    });
}

//根据经纬度反编码，获取详细地址信息
function geocoder(lnglatXY)
{
    var MGeocoder;
    //加载地理编码插件
    mapObj.plugin(["AMap.Geocoder"], function() {
        MGeocoder = new AMap.Geocoder({
            radius: 1000,
            extensions: "all"
        });
        //返回地理编码结果 
        AMap.event.addListener(MGeocoder, "complete", geocoder_CallBack);
        //逆地理编码
        MGeocoder.getAddress(lnglatXY);
    });
}

//回调函数
function geocoder_CallBack(data) {
    //返回地址描述
    var address = data.regeocode.formattedAddress;
    init_yaoheFormTemplate(currentMarker.getPosition(), address);
    infoWindow.setContent(createInfoWindow(yaoheFormTemplate.join("")));
    infoWindow.open(mapObj, currentMarker.getPosition());
    mapObj.setCenter(currentMarker.getPosition());

//    $("canvas").each(function() {
//        var c = $(this).getImageData(0, 0, 300, 300);
//        ctx.putImageData(0, 0, 300, 300);
//    });
    //var image = $("canvas")[0].toDataURL("image/png").replace("image/png", "image/octet-stream");
//    var ctx = $("canvas")[0].getContext("2d");
//    var image = ctx.getImageData(currentX, currentY, 230, 150);
//    var ctx = $("canvas")[1].getContext("2d");
//    var image1 = ctx.getImageData(currentX, currentY, 230, 150);
//    window.setTimeout(function() {
//        var ctxyaohe = document.getElementById("ctx").getContext("2d");
//        ctxyaohe.putImageData(image, 0, 0);
//        ctxyaohe.putImageData(image1, 0, 0);
//        var img = new Image();
//        img.src = "http://api.iyaohe.cc/images/lvse.png";
//        ctxyaohe.drawImage(img, 86, 60);
//    }, 1000);
    //window.location.href=image; // it will save locally  
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
        markers = [];
        mapObj.clearMap();
    }
    leftDiv.innerHTML = "";
    if (infoWindow != null)
    {
        infoWindow.close();
    }
}

//构建自定义信息窗体	
function createInfoWindow(content) {
    var info = document.createElement("div");
    info.className = "info";

    //可以通过下面的方式修改自定义窗体的宽高
    info.style.width = "350px";

    // 定义顶部标题
    var top = document.createElement("div");
    top.className = "info-top";
    var titleD = document.createElement("div");
    titleD.innerHTML = '恰好';
    var closeX = document.createElement("img");
    closeX.src = "http://webapi.amap.com/images/close2.gif";
    closeX.onclick = closeInfoWindow;

    top.appendChild(titleD);
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
}

//关闭信息窗体
function closeInfoWindow() {
    mapObj.clearInfoWindow();
    if (currentMarker != null)
    {
        currentMarker.setMap(null);
    }
}