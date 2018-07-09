<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=3VtaMWkKuR367HDWhEGok2Ik"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>
    <title>职位分布图</title>
    <style type="text/css">
        html {
            height: 100%;
        }

        body {
            margin: 0;
            height: 100%
        }

        #container {
            min-height: 100%;
            width: 100%;
        }
    </style>
</head>
<body>
<div id="container"></div>
</body>
</html>
@php
    $points = [];
    $file = fopen(storage_path('app/public') ."/positions_PHP.csv","r");
    while(! feof($file))
    {
        $item = fgetcsv($file);
        $points[] = ['lng'=>$item['23'],'lat'=>$item['24'],'count'=>1];
    }
    fclose($file);
@endphp

<script type="text/javascript">
    var map = new BMap.Map("container");          // 创建地图实例

    var point = new BMap.Point(116.418261, 30.921984);
    map.centerAndZoom(point, 6);             // 初始化地图，设置中心点坐标和地图级别
    map.enableScrollWheelZoom(); // 允许滚轮缩放

    var points = {!! json_encode($points,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!};

    if (!isSupportCanvas()) {
        alert('热力图目前只支持有canvas支持的浏览器,您所使用的浏览器不能使用热力图功能~')
    }
    //详细的参数,可以查看heatmap.js的文档 https://github.com/pa7/heatmap.js/blob/master/README.md
    //参数说明如下:
    /* visible 热力图是否显示,默认为true
     * opacity 热力的透明度,1-100
     * radius 势力图的每个点的半径大小   
     * gradient  {JSON} 热力图的渐变区间 . gradient如下所示
     *	{
            .2:'rgb(0, 255, 255)',
            .5:'rgb(0, 110, 255)',
            .8:'rgb(100, 0, 255)'
        }
        其中 key 表示插值的位置, 0~1.
            value 为颜色值.
     */
    heatmapOverlay = new BMapLib.HeatmapOverlay({"radius": 15});
    map.addOverlay(heatmapOverlay);
    heatmapOverlay.setDataSet({data: points, max: 20});

    //是否显示热力图
    function openHeatmap() {
        heatmapOverlay.show();
    }

    function closeHeatmap() {
        heatmapOverlay.hide();
    }

    closeHeatmap();

    function setGradient() {
        /*格式如下所示:
       {
             0:'rgb(102, 255, 0)',
             .5:'rgb(255, 170, 0)',
             1:'rgb(255, 0, 0)'
       }*/
        var gradient = {};
        var colors = document.querySelectorAll("input[type='color']");
        colors = [].slice.call(colors, 0);
        colors.forEach(function (ele) {
            gradient[ele.getAttribute("data-key")] = ele.value;
        });
        heatmapOverlay.setOptions({"gradient": gradient});
    }

    //判断浏览区是否支持canvas
    function isSupportCanvas() {
        var elem = document.createElement('canvas');
        return !!(elem.getContext && elem.getContext('2d'));
    }

    openHeatmap();
</script>